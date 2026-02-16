<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScrapedContact;

class ScrapeWebsite extends Command
{
    protected $signature = 'scrape:run {url} {--client=}';
    protected $description = 'Run Scrapy for a given URL';

    public function handle()
{
    $url = $this->argument('url');
    $clientId = $this->option('client');

    if (!$clientId) {
        $this->error('Client ID manquant');
        return Command::FAILURE;
    }

    $path = base_path('scraper/result.json');
    $scraperPath = base_path('scraper');

    // ✅ NETTOYAGE COMPLET
    if (file_exists($path)) {
        unlink($path);
    }
    
    // Supprimer aussi les fichiers temporaires Scrapy
    $tempFiles = glob($scraperPath . '/*.json.tmp');
    foreach ($tempFiles as $file) {
        unlink($file);
    }

    // ✅ FORCER l'utilisation de la nouvelle URL
    $cmd = sprintf(
        'cd %s && scrapy crawl contacts -a url=%s -O result.json --nolog',
        escapeshellarg($scraperPath),
        escapeshellarg($url)
    );

    $this->info("Exécution: " . $cmd);
    exec($cmd . ' 2>&1', $output, $returnCode);
    
    // Log pour debug
    $this->info("Return code: " . $returnCode);
    $this->info("Output: " . implode("\n", $output));

    if (!file_exists($path)) {
        $this->error('Fichier result.json introuvable');
        return Command::FAILURE;
    }

    $data = json_decode(file_get_contents($path), true);

    if (!is_array($data)) {
        $this->error('Résultat Scrapy invalide');
        return Command::FAILURE;
    }

    foreach ($data as $item) {
        ScrapedContact::create([
            'client_id'  => $clientId,
            'name'       => $item['name'] ?? null,
            'email'      => $item['email'] ?? null,
            'source_url' => $item['source_url'] ?? $url,
            'facebook'   => $item['facebook'] ?? null,
            'instagram'  => $item['instagram'] ?? null,
            'linkedin'   => $item['linkedin'] ?? null,
        ]);
    }

    $this->info('Scraping terminé pour: ' . $url);
    return Command::SUCCESS;
}

}
