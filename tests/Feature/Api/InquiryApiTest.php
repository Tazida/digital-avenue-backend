<?php

namespace Tests\Feature\Api;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Models\Service;
use App\Services\TurnstileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\Concerns\CallsInternalApi;
use Tests\TestCase;

class InquiryApiTest extends TestCase
{
    use CallsInternalApi, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpInternalApi();
        Mail::fake();
        RateLimiter::clear('inquiries-minute:203.0.113.7');
    }

    private function form(array $overrides = []): array
    {
        return $overrides + [
            'name'    => 'Rahim Uddin',
            'email'   => 'rahim@example.com',
            'phone'   => '+880 1711-000000',
            'company' => 'Test Builders Ltd',
            'message' => 'We need maintenance dredging near our jetty. Please send a quote.',
        ];
    }

    private function send(array $data, string $ip = '203.0.113.7')
    {
        return $this->api('POST', 'inquiries', $data, ['X-Visitor-IP' => $ip]);
    }

    public function test_saves_the_inquiry_and_queues_the_email_alert(): void
    {
        $service = Service::factory()->create(['slug' => 'river-dredging']);

        $this->send($this->form(['service' => 'river-dredging']))
            ->assertCreated()
            ->assertJsonPath('success', true);

        $inquiry = Inquiry::sole();
        $this->assertSame('new', $inquiry->status);
        $this->assertTrue($inquiry->service->is($service));

        Mail::assertQueued(InquiryReceived::class, fn (InquiryReceived $mail) => $mail->hasTo('digitalavenuebd@gmail.com')
            && $mail->hasReplyTo('rahim@example.com')
            && $mail->inquiry->is($inquiry));
    }

    public function test_validates_the_form(): void
    {
        $this->send(['name' => '', 'email' => 'nope', 'phone' => 'call me', 'message' => 'Hi'])
            ->assertUnprocessable()
            ->assertJsonPath('error', true)
            ->assertJsonStructure(['message' => ['name', 'email', 'phone', 'message']]);

        $this->assertSame(0, Inquiry::count());
        Mail::assertNothingQueued();
    }

    public function test_rejects_unpublished_services(): void
    {
        Service::factory()->unpublished()->create(['slug' => 'hidden']);

        $this->send($this->form(['service' => 'hidden']))->assertUnprocessable()->assertJsonStructure(['message' => ['service']]);
    }

    public function test_the_honeypot_stops_bots(): void
    {
        $this->send($this->form(['website' => 'http://spam.example']))->assertUnprocessable();

        $this->assertSame(0, Inquiry::count());
    }

    public function test_limits_each_visitor_to_three_messages_a_minute(): void
    {
        foreach (range(1, 3) as $i) {
            $this->send($this->form())->assertCreated();
        }

        $this->send($this->form())->assertTooManyRequests()->assertJsonPath('error', true);

        // Another visitor is not affected.
        RateLimiter::clear('inquiries-minute:198.51.100.9');
        $this->send($this->form(), '198.51.100.9')->assertCreated();
    }

    public function test_checks_turnstile_when_a_secret_is_configured(): void
    {
        config(['services.turnstile.secret' => 'test-secret']);
        Http::fake([
            'challenges.cloudflare.com/*' => Http::sequence()
                ->push(['success' => false])
                ->push(['success' => true]),
        ]);

        $this->send($this->form(['turnstile_token' => 'bad']))
            ->assertUnprocessable()
            ->assertJsonStructure(['message' => ['turnstile_token']]);
        $this->assertSame(0, Inquiry::count());

        $this->send($this->form(['turnstile_token' => 'good']))->assertCreated();
        $this->assertSame(1, Inquiry::count());

        Http::assertSent(fn (HttpRequest $request) => $request['secret'] === 'test-secret'
            && $request['response'] === 'good'
            && $request['remoteip'] === '203.0.113.7');
    }

    public function test_turnstile_fails_closed_in_production_without_a_secret(): void
    {
        config(['services.turnstile.secret' => null]);
        $this->app['env'] = 'production';

        $this->assertFalse(app(TurnstileService::class)->verify('any-token'));
    }
}
