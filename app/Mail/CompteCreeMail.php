<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompteCreeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $motDePasseTemporaire
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre espace Archivage DGTCP est prêt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.compte_cree',
        );
    }
}