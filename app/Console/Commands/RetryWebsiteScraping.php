<?php
// app/Console/Commands/RetryWebsiteScraping.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GooglePlace;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RetryWebsiteScraping extends Command
{
    protected $signature = 'scrape:retry-websites 
                            {--client= : ID du client}
                            {--limit=50 : Nombre maximum de sites à scraper}';
    
    protected $description = 'Relancer le scraping des sites web non traités';

    public function handle()
    {
        $clientId = $this->option('client');
        $limit = (int) $this->option('limit');

        $query = GooglePlace::query()
            ->whereNotNull('website')
            ->whereNull('contact_scraped_at');

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        $total = $query->count();
        $places = $query->limit($limit)->get();

        $this->info("🔍 {$total} sites web à traiter trouvés");
        $this->info("🚀 Lancement du scraping pour {$places->count()} sites...");

        $bar = $this->output->createProgressBar($places->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($places as $place) {
            try {
                Artisan::call('scrape:website', [
                    'google_place_id' => $place->id,
                    'url' => $place->website,
                    '--client' => $place->client_id,
                ]);

                $output = Artisan::output();
                
                if (str_contains($output, 'Emails trouvés')) {
                    $success++;
                } else {
                    $failed++;
                }

                sleep(1);

            } catch (\Exception $e) {
                $failed++;
                Log::error('Erreur retry scraping: ' . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("📊 Résultats: {$success} succès, {$failed} échecs");

        return Command::SUCCESS;
    }
}