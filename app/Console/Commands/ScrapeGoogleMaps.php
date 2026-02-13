<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GooglePlace;

class ScrapeGoogleMaps extends Command
{
    protected $signature = 'scrape:google {query} {--client=}';
    protected $description = 'Scrape Google Maps results';

    public function handle()
{
    $query = (string) $this->argument('query');
    $clientId = $this->option('client');

    if (!$query) {
        $this->error('Requête Google manquante');
        return Command::FAILURE;
    }

    $cmd = 'cd ../scraper && scrapy crawl google_maps -a query="' . addslashes($query) . '" -O google.json';
    exec($cmd . ' > NUL 2>&1');

    $path = base_path('scraper/google.json');

    if (!file_exists($path)) {
        $this->error('google.json introuvable');
        return Command::FAILURE;
    }

    $data = json_decode(file_get_contents($path), true);

    foreach ($data as $item) {
        \App\Models\GooglePlace::create([
            'client_id' => $clientId,
            'name'      => $item['name'] ?? null,
            'category'  => $item['category'] ?? null,
            'address'   => $item['address'] ?? null,
        ]);
    }

    $this->info('Google Maps scraping terminé');
    return Command::SUCCESS;
}

}
