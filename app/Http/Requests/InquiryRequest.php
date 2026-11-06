<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

/**
 * POST /inquiries — the contact form. "website" is a hidden honeypot field:
 * people leave it empty, bots fill it in.
 */
class InquiryRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:120'],
            'email'           => ['required', 'email', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{6,30}$/'],
            'company'         => ['nullable', 'string', 'max:255'],
            'service'         => ['nullable', 'string', Rule::exists('services', 'slug')->where('is_published', true)],
            'message'         => ['required', 'string', 'min:10', 'max:5000'],
            'turnstile_token' => ['nullable', 'string', 'max:2048'],
            'website'         => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex'        => 'Enter a valid phone number.',
            'website.prohibited' => 'Your message could not be sent.',
        ];
    }
}
