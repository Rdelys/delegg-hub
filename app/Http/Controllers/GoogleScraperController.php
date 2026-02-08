<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GooglePlace;
use Barryvdh\DomPDF\Facade\Pdf;

class GoogleScraperController extends Controller
{
    public function index()
    {
        $places = GooglePlace::where('client_id', session('client.id'))
            ->latest()
            ->paginate(10);

        return view('client.google', compact('places'));
    }

    public function scrape(Request $request)
{
    $request->validate([
        'query' => 'required|string'
    ]);

    $response = Http::get('https://serpapi.com/search.json', [
        'engine' => 'google_maps',
        'q' => $request->input('query'),
        'hl' => 'fr',
        'gl' => 'fr',
        'api_key' => env('SERPAPI_KEY'),
    ]);

    if (!$response->ok()) {
        return back()->with('success', 'Erreur SerpAPI');
    }

    $results = $response->json('local_results');

    if (empty($results)) {
        return back()->with('success', 'Aucun résultat trouvé');
    }

    foreach ($results as $place) {
        \App\Models\GooglePlace::create([
            'client_id' => session('client.id'),
            'name'      => $place['title'] ?? null,
            'category'  => $place['type'] ?? null,
            'address'   => $place['address'] ?? null,
            'phone'     => $place['phone'] ?? null,
            'website'   => $place['website'] ?? null,
        ]);
    }

    return back()->with('success', 'Analyse Google Maps terminée');
}

public function exportPdf()
    {
        // ⚠️ IMPORTANT :
        // on exporte TOUS les résultats du client connecté
        $clientId = session('client.id');

        $places = GooglePlace::where('client_id', $clientId)
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('client.google-pdf', [
            'places' => $places
        ])->setPaper('a4', 'portrait');

        return $pdf->download('google-maps-entreprises.pdf');
    }

}
