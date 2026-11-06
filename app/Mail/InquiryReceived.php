<?php

namespace App\Mail;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Alert to the company inbox when someone sends the contact form.
 * "Reply" in the mail app answers the visitor directly.
 */
class InquiryReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New website inquiry from '.$this->inquiry->name,
            replyTo: [new Address($this->inquiry->email, $this->inquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.inquiry-received',
            with: [
                'inquiry'  => $this->inquiry->loadMissing('service'),
                'inboxUrl' => InquiryResource::getUrl(panel: 'admin'),
            ],
        );
    }
}
