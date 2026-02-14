<?php
// app/Console/Commands/ScrapeWebsiteForGooglePlace.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GooglePlace;
use Illuminate\Support\Facades\Log;

class ScrapeWebsiteForGooglePlace extends Command
{
    protected $signature = 'scrape:website {google_place_id} {url} {--client=}';
    protected $description = 'Scrape website for a specific Google Place';

    public function handle()
    {
        $googlePlaceId = $this->argument('google_place_id');
        $url = $this->argument('url');
        $clientId = $this->option('client');

        if (!$clientId) {
            $this->error('Client ID manquant');
            return Command::FAILURE;
        }

        $googlePlace = GooglePlace::find($googlePlaceId);
        
        if (!$googlePlace) {
            $this->error('Google Place non trouvé');
            return Command::FAILURE;
        }

        $path = base_path('scraper/result.json');
        $scraperPath = base_path('scraper');

        // Nettoyage
        if (file_exists($path)) {
            unlink($path);
        }
        
        $tempFiles = glob($scraperPath . '/*.json.tmp');
        foreach ($tempFiles as $file) {
            unlink($file);
        }

        // Exécuter le spider Scrapy
        $cmd = sprintf(
            'cd %s && scrapy crawl contacts -a url=%s -O result.json --nolog',
            escapeshellarg($scraperPath),
            escapeshellarg($url)
        );

        $this->info("Exécution: " . $cmd);
        exec($cmd . ' 2>&1', $output, $returnCode);
        
        $this->info("Return code: " . $returnCode);

        if (!file_exists($path)) {
            $this->error('Fichier result.json introuvable');
            
            // Marquer comme scrappé même si aucun résultat
            $googlePlace->update([
                'website_scraped' => true,
                'website_scraped_at' => now(),
            ]);
            
            return Command::FAILURE;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data) || empty($data)) {
            $this->info('Aucun contact trouvé');
            
            // Marquer comme scrappé
            $googlePlace->update([
                'website_scraped' => true,
                'website_scraped_at' => now(),
            ]);
            
            return Command::SUCCESS;
        }

        // Prendre le premier résultat (ou fusionner plusieurs si nécessaire)
        $firstResult = $data[0] ?? [];

        // Mettre à jour GooglePlace avec les informations trouvées
        $updateData = [
            'website_scraped' => true,
            'website_scraped_at' => now(),
            'contact_scraped_at' => now(),
        ];

        // Ajouter les emails (si plusieurs, on les concatène)
        $emails = array_column($data, 'email');
        $emails = array_filter($emails);
        if (!empty($emails)) {
            $updateData['email'] = implode(', ', array_unique($emails));
        }

        // Réseaux sociaux (prendre le premier trouvé)
        $facebook = array_column($data, 'facebook');
        $facebook = array_filter($facebook);
        if (!empty($facebook)) {
            $updateData['facebook'] = reset($facebook);
        }

        $instagram = array_column($data, 'instagram');
        $instagram = array_filter($instagram);
        if (!empty($instagram)) {
            $updateData['instagram'] = reset($instagram);
        }

        $linkedin = array_column($data, 'linkedin');
        $linkedin = array_filter($linkedin);
        if (!empty($linkedin)) {
            $updateData['linkedin'] = reset($linkedin);
        }

        // Source URL
        $updateData['source_url'] = $url;

        $googlePlace->update($updateData);

        $this->info('Scraping terminé pour: ' . $url);
        $this->info('Emails trouvés: ' . ($updateData['email'] ?? 'aucun'));
        
        return Command::SUCCESS;
    }
}