<?php

namespace App\Services;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Models\Service;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Contact-form submissions: spam check, save to the admin inbox, email alert.
 */
class InquiryService
{
    public function __construct(private TurnstileService $turnstile) {}

    /**
     * @param  array{name: string, email: string, phone?: ?string, company?: ?string, service?: ?string, message: string, turnstile_token?: ?string}  $data
     */
    public function submit(array $data, ?string $visitorIp): Inquiry
    {
        if (! $this->turnstile->verify($data['turnstile_token'] ?? null, $visitorIp)) {
            throw ValidationException::withMessages([
                'turnstile_token' => 'The spam check failed. Please try again.',
            ]);
        }

        $inquiry = Inquiry::create([
            'service_id' => filled($data['service'] ?? null)
                ? Service::published()->where('slug', $data['service'])->value('id')
                : null,
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'message' => $data['message'],
            'status'  => 'new',
        ]);

        // Queued: the visitor gets an answer right away, the worker sends the email.
        Mail::to(config('services.inquiries.alert_email'))->send(new InquiryReceived($inquiry));

        return $inquiry;
    }
}
