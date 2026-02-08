<?php

namespace App\Http\Controllers;

use App\Models\ScrapedContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Barryvdh\DomPDF\Facade\Pdf;

class WebScraperController extends Controller
{
    public function index()
{
    $results = \App\Models\ScrapedContact::latest()->paginate(10);

    return view('client.web', compact('results'));
}


    public function scrape(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        Artisan::call('scrape:run', [
            'url' => $request->url
        ]);

        return redirect()
            ->route('client.web')
            ->with('success', 'Scraping terminé');
    }

    public function exportPdf()
{
    $results = ScrapedContact::latest()->get();

    $pdf = Pdf::loadView('client.web-pdf', [
        'results' => $results
    ])->setPaper('a4', 'portrait');

    return $pdf->download('web-scraper-results.pdf');
}
}

