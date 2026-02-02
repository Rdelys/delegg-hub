<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Licence;
use Carbon\Carbon;

class ExpireLicencesCommand extends Command
{
    protected $signature = 'licences:expire';
    protected $description = 'Expire automatiquement les licences';

    public function handle()
    {
        $licences = Licence::where('status', 'actif')
            ->whereDate('end_date', '<', Carbon::today())
            ->with('client')
            ->get();

        foreach ($licences as $licence) {

            // Expire licence
            $licence->update([
                'status' => 'expire'
            ]);

            // Vérifier si le client a encore une licence active
            $hasActive = $licence->client
                ->licences()
                ->where('status', 'actif')
                ->exists();

            if (!$hasActive) {
                $licence->client->update([
                    'status' => 'inactif'
                ]);
            }
        }

        $this->info('Licences expirées : ' . $licences->count());
    }
}
