<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\Licence;
use Illuminate\Mail\Mailable;

class LicenceExpiringMail extends Mailable
{
    public function __construct(
        public Client $client,
        public Licence $licence,
        public int $daysLeft
    ) {}

    public function build()
    {
        return $this
            ->subject('Votre licence expire bientôt')
            ->view('emails.licence-expiring');
    }
}
