<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'test-internal-token';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend.token' => self::TOKEN]);
    }

    private function list(string $query = '', string $token = self::TOKEN)
    {
        return $this->getJson('/api/v1.0/clients'.$query, ['X-Internal-Token' => $token]);
    }

    public function test_lists_clients_in_sort_order(): void
    {
        Client::factory()->create(['name' => 'WFP', 'sort_order' => 2]);
        Client::factory()->create(['name' => 'IOM', 'sort_order' => 1]);
        Client::factory()->create(['name' => 'BIWTA', 'sort_order' => 2]);

        $this->list()
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.*.name', ['IOM', 'BIWTA', 'WFP']);
    }

    public function test_filters_by_type(): void
    {
        Client::factory()->create(['name' => 'UNHCR', 'type' => 'un_agency']);
        Client::factory()->create(['name' => 'CPA', 'type' => 'government']);

        $this->list('?type=government')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'CPA');
    }

    public function test_hides_the_logo_until_show_logo_is_switched_on(): void
    {
        Client::factory()->create(['name' => 'IOM', 'logo_url' => 'https://example.com/iom.svg', 'show_logo' => false, 'sort_order' => 1]);
        Client::factory()->create(['name' => 'CCCC', 'logo_url' => 'https://example.com/cccc.svg', 'show_logo' => true, 'sort_order' => 2]);

        $this->list()
            ->assertOk()
            ->assertJsonPath('data.0.logo_url', null)
            ->assertJsonPath('data.1.logo_url', 'https://example.com/cccc.svg');
    }

    public function test_rejects_an_unknown_type(): void
    {
        $this->list('?type=unknown')
            ->assertUnprocessable()
            ->assertJsonPath('error', true)
            ->assertJsonStructure(['message' => ['type']]);
    }

    public function test_refuses_calls_without_the_internal_token(): void
    {
        $this->getJson('/api/v1.0/clients')->assertUnauthorized()->assertJsonPath('error', true);
        $this->list('', 'wrong-token')->assertUnauthorized();
    }

    public function test_refuses_every_call_when_no_token_is_configured(): void
    {
        config(['services.frontend.token' => null]);

        $this->list('', '')->assertUnauthorized();
    }
}
