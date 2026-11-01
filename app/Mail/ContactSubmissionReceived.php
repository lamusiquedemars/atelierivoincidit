<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maracuja\SiteCore\Contact\Models\Inquiry;

class ContactSubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $submission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->submission->email],
            subject: 'Nouveau message depuis le site',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-submission-received',
        );
    }
}
