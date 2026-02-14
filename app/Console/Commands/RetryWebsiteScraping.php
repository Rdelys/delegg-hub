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
                            {--limit=50 : Nombre maximum de sites à scraper}
                            {--force : Forcer le scraping même si déjà fait}';
    
    protected $description = 'Relancer le scraping des sites web non traités';

    public function handle()
    {
        $clientId = $this->option('client');
        $limit = (int) $this->option('limit');
        $force = $this->option('force');

        $query = GooglePlace::query();

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        // Sites avec website mais sans contacts scrappés
        if (!$force) {
            $query->whereNotNull('website')
                  ->whereNull('contact_scraped_at');
        } else {
            // Forcer le scraping même si déjà fait
            $query->whereNotNull('website');
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
                $this->line("\n");
                $this->info("📡 Scraping: {$place->website}");

                Artisan::call('scrape:website', [
                    'google_place_id' => $place->id,
                    'url' => $place->website,
                    '--client' => $place->client_id,
                ]);

                $output = Artisan::output();
                
                if (str_contains($output, 'Emails trouvés')) {
                    $success++;
                    $this->info("✅ Succès: {$place->website}");
                } else {
                    $failed++;
                    $this->warn("⚠️ Aucun contact trouvé: {$place->website}");
                }

                // Pause pour éviter de surcharger
                sleep(2);

            } catch (\Exception $e) {
                $failed++;
                $this->error("❌ Erreur: {$place->website} - {$e->getMessage()}");
                Log::error('Erreur retry scraping: ' . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);
        $this->info("📊 Résultats du scraping:");
        $this->table(
            ['Statut', 'Nombre'],
            [
                ['✅ Succès', $success],
                ['⚠️ Sans résultat', $failed - $failed],
                ['❌ Échec', $failed],
                ['📊 Total', $places->count()],
            ]
        );

        return Command::SUCCESS;
    }
}