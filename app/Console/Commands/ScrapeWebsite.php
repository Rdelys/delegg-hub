<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeWebsite extends Command
{
    protected $signature = 'scrape:run {url}';
protected $description = 'Run Scrapy for a given URL';

public function handle()
{
    $url = $this->argument('url');

$cmd = 'cd ../scraper && scrapy crawl contacts -a url="'.$url.'" -O result.json';
exec($cmd . ' > NUL 2>&1');

$data = json_decode(
    file_get_contents('../scraper/result.json'),
    true
);


    foreach ($data as $item) {
        \App\Models\ScrapedContact::create($item);
    }

    $this->info('Scraping terminé');
}
}
