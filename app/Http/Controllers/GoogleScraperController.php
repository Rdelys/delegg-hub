<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GooglePlace;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GoogleScraperController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX + FILTRE PAR NOM SCRAPPING
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
{
    $clientId = session('client.id');

    if (!$clientId) {
        return redirect()->back()->with('error', 'Session expirée');
    }

    $query = GooglePlace::where('client_id', $clientId);

    // 🔥 FILTRE PAR NOM SCRAPPING
    if ($request->filled('filter_scrapping')) {
        $query->where('nom_scrapping', $request->filter_scrapping);
    }

    $places = $query
        ->orderByDesc('rating')
        ->paginate(10)
        ->withQueryString(); // ⚠️ IMPORTANT POUR GARDER LE FILTRE EN PAGINATION

    $scrappings = GooglePlace::where('client_id', $clientId)
        ->whereNotNull('nom_scrapping')
        ->distinct()
        ->pluck('nom_scrapping');

    return view('client.google', compact('places', 'scrappings'));
}


    /*
    |--------------------------------------------------------------------------
    | SCRAPE GOOGLE MAPS + SAVE NOM SCRAPPING
    |--------------------------------------------------------------------------
    */
    public function scrape(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'nom_scrapping' => 'required|string|max:255',
        ]);

        $clientId = session('client.id');

        if (!$clientId) {
            return back()->with('error', 'Session expirée');
        }

        $queryInput = $request->input('query');
        $nomScrapping = $request->input('nom_scrapping');

        $response = Http::timeout(60)->get('https://serpapi.com/search.json', [
            'engine' => 'google_maps',
            'q' => $queryInput,
            'hl' => 'fr',
            'gl' => 'fr',
            'api_key' => config('services.serpapi.key'),
        ]);

        if (!$response->ok()) {
            Log::error('Erreur SerpAPI: ' . $response->body());
            return back()->with('error', 'Erreur SerpAPI');
        }

        $results = $response->json('local_results');

        if (!$results) {
            return back()->with('info', 'Aucun résultat trouvé');
        }

        foreach ($results as $place) {

            $googlePlace = GooglePlace::updateOrCreate(
                [
                    'client_id' => $clientId,
                    'website' => $place['website'] ?? null,
                    'name' => $place['title'] ?? null,
                ],
                [
                    'nom_scrapping' => $nomScrapping,
                    'category' => $place['type'] ?? null,
                    'address' => $place['address'] ?? null,
                    'phone' => $place['phone'] ?? null,
                    'rating' => $place['rating'] ?? null,
                    'reviews_count' => $place['reviews'] ?? null,
                ]
            );

            // Lancer scraping site web
            if ($googlePlace->website && !$googlePlace->contact_scraped_at) {
                try {
                    Artisan::queue('scrape:website', [
                        'google_place_id' => $googlePlace->id,
                        'url' => $googlePlace->website,
                        '--client' => $clientId,
                    ]);

                    Log::info('Scraping website lancé: ' . $googlePlace->website);

                } catch (\Exception $e) {
                    Log::error('Erreur scraping website: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('client.google')
            ->with('success', 'Scraping Google + Web terminé');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF (AVEC FILTRE)
    |--------------------------------------------------------------------------
    */
    public function exportPdf(Request $request)
    {
        $clientId = session('client.id');

        $query = GooglePlace::where('client_id', $clientId);

        if ($request->filled('filter_scrapping')) {
            $query->where('nom_scrapping', $request->filter_scrapping);
        }

        $places = $query->orderBy('name')->get();

        $pdf = Pdf::loadView('client.google-pdf', [
            'places' => $places
        ])->setPaper('a4', 'portrait');

        return $pdf->download('google-maps-entreprises.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SELECTED
    |--------------------------------------------------------------------------
    */
    public function deleteSelected(Request $request)
    {
        $request->validate([
            'selected' => 'required|array'
        ]);

        $clientId = session('client.id');

        GooglePlace::where('client_id', $clientId)
            ->whereIn('id', $request->selected)
            ->delete();

        return back()->with('success', 'Sélection supprimée avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL (AVEC NOM SCRAPPING)
    |--------------------------------------------------------------------------
    */
    public function exportExcel(Request $request)
    {
        $clientId = session('client.id');

        $query = GooglePlace::where('client_id', $clientId);

        if ($request->filled('filter_scrapping')) {
            $query->where('nom_scrapping', $request->filter_scrapping);
        }

        $places = $query->orderByDesc('rating')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Nom Scrapping',
            'Entreprise',
            'Catégorie',
            'Adresse',
            'Téléphone',
            'Site Web',
            'Email',
            'Facebook',
            'Instagram',
            'LinkedIn',
            'Note',
            'Nombre d\'avis',
            'Website Scrappé',
            'Contacts Scrappés'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $row = 2;

        foreach ($places as $p) {
            $sheet->setCellValue('A' . $row, $p->nom_scrapping);
            $sheet->setCellValue('B' . $row, $p->name);
            $sheet->setCellValue('C' . $row, $p->category);
            $sheet->setCellValue('D' . $row, $p->address);
            $sheet->setCellValue('E' . $row, $p->phone);
            $sheet->setCellValue('F' . $row, $p->website);
            $sheet->setCellValue('G' . $row, $p->email);
            $sheet->setCellValue('H' . $row, $p->facebook);
            $sheet->setCellValue('I' . $row, $p->instagram);
            $sheet->setCellValue('J' . $row, $p->linkedin);
            $sheet->setCellValue('K' . $row, $p->rating);
            $sheet->setCellValue('L' . $row, $p->reviews_count);
            $sheet->setCellValue('M' . $row, $p->website_scraped ? 'Oui' : 'Non');
            $sheet->setCellValue('N' . $row, $p->contact_scraped_at ? 'Oui' : 'Non');
            $row++;
        }

        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $fileName = 'google-maps-entreprises.xlsx';
        $filePath = storage_path('app/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /*
    |--------------------------------------------------------------------------
    | RETRY SCRAPING
    |--------------------------------------------------------------------------
    */
    public function retryScraping()
    {
        $clientId = session('client.id');

        if (!$clientId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expirée'
            ], 401);
        }

        $pendingCount = GooglePlace::where('client_id', $clientId)
            ->whereNotNull('website')
            ->whereNull('contact_scraped_at')
            ->count();

        if ($pendingCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun site web en attente'
            ]);
        }

        try {
            Artisan::queue('scrape:retry-websites', [
                '--client' => $clientId,
                '--limit' => 50,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Scraping lancé pour {$pendingCount} sites",
                'count' => $pendingCount
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur retry scraping: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du lancement'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STATS
    |--------------------------------------------------------------------------
    */
    public function getScrapingStats()
    {
        $clientId = session('client.id');

        if (!$clientId) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $stats = [
            'total' => GooglePlace::where('client_id', $clientId)->count(),
            'with_website' => GooglePlace::where('client_id', $clientId)->whereNotNull('website')->count(),
            'scraped' => GooglePlace::where('client_id', $clientId)->whereNotNull('contact_scraped_at')->count(),
            'pending' => GooglePlace::where('client_id', $clientId)
                ->whereNotNull('website')
                ->whereNull('contact_scraped_at')
                ->count(),
            'with_email' => GooglePlace::where('client_id', $clientId)
                ->whereNotNull('email')
                ->count(),
        ];

        return response()->json($stats);
    }
}
