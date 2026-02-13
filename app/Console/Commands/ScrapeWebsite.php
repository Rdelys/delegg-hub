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

    // ✅ SUPPRIMER L’ANCIEN FICHIER
    if (file_exists($path)) {
        unlink($path);
    }

    // ✅ Lancer Scrapy
    $cmd = 'cd ' . base_path('scraper') .
       ' && scrapy crawl contacts -a url="' . $url . '" -O result.json --nolog';

    exec($cmd);

    // 🔎 Vérifier si le nouveau fichier existe
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
        ]);
    }

    $this->info('Scraping terminé');
    return Command::SUCCESS;
}

}
