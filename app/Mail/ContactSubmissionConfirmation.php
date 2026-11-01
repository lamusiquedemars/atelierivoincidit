<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maracuja\SiteCore\Contact\Models\Inquiry;

class ContactSubmissionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $submission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de réception de votre message',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-submission-confirmation',
        );
    }
}
