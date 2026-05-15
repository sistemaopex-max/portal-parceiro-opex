<?php

namespace App\Mail;

use App\Models\PartnerInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly PartnerInvitation $invitation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convite para cadastro no Portal Opex',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-invitation',
            with: [
                'invitation' => $this->invitation,
                'url' => route('invitation.show', $this->invitation->token),
            ],
        );
    }
}
