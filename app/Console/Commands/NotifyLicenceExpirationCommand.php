<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Licence;
use App\Mail\LicenceExpiringMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotifyLicenceExpirationCommand extends Command
{
    protected $signature = 'licences:notify-expiring {days=7}';
    protected $description = 'Envoie un email avant expiration de licence';

    public function handle()
    {
        $days = (int) $this->argument('days');
        $targetDate = Carbon::now()->addDays($days)->toDateString();

        $licences = Licence::where('status', 'actif')
            ->whereDate('end_date', $targetDate)
            ->where('expiration_notified', false)
            ->with('client')
            ->get();

        foreach ($licences as $licence) {

            if (!$licence->client->email) {
                continue;
            }

            Mail::to($licence->client->email)
                ->send(new LicenceExpiringMail(
                    $licence->client,
                    $licence,
                    $days
                ));

            $licence->update([
                'expiration_notified' => true
            ]);
        }

        $this->info('Notifications envoyées : ' . $licences->count());
    }
}
