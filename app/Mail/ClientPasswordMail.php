<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientPasswordMail extends Mailable
{
    public function __construct(
        public string $password
    ) {}

    public function build()
    {
        return $this
            ->subject('Votre accès client')
            ->view('emails.client-password');
    }
}