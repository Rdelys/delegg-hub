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
    $clientId = session('client.id');

    $results = ScrapedContact::where('client_id', $clientId)
        ->latest()
        ->paginate(10);

    return view('client.web', compact('results'));
}



    public function scrape(Request $request)
{
    $request->validate([
        'url' => 'required|url'
    ]);

    Artisan::call('scrape:run', [
        'url' => $request->url,
        '--client' => session('client.id'), // 👈 clé
    ]);

    return redirect()
        ->route('client.web')
        ->with('success', 'Scraping terminé');
}


    public function exportPdf()
{
    $clientId = session('client.id');

    $results = ScrapedContact::where('client_id', $clientId)
        ->latest()
        ->get();

    $pdf = Pdf::loadView('client.web-pdf', [
        'results' => $results
    ])->setPaper('a4', 'portrait');

    return $pdf->download('web-scraper-results.pdf');
}

}

