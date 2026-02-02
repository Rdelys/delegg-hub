<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\Licence;
use Illuminate\Mail\Mailable;

class LicenceActivatedMail extends Mailable
{
    public function __construct(
        public Client $client,
        public Licence $licence
    ) {}

    public function build()
    {
        return $this
            ->subject('Votre licence est active')
            ->view('emails.licence-activated');
    }
}
