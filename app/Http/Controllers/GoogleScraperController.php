<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GooglePlace;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;

class GoogleScraperController extends Controller
{
    public function index()
    {
        $places = GooglePlace::where('client_id', session('client.id'))
    ->orderByDesc('rating')
    ->paginate(10);


        return view('client.google', compact('places'));
    }

    public function scrape(Request $request)
{
    $request->validate([
        'query' => 'required|string'
    ]);

    $clientId = session('client.id');

    $response = Http::get('https://serpapi.com/search.json', [
    'engine' => 'google_maps',
    'q' => $request->input('query'),
    'hl' => 'fr',
    'gl' => 'fr',
    'api_key' => config('services.serpapi.key'), // Use config() instead of env()
]);

    if (!$response->ok()) {
        return back()->with('success', 'Erreur SerpAPI');
    }

    $results = $response->json('local_results');

    if (!$results) {
        return back()->with('success', 'Aucun résultat');
    }

    foreach ($results as $place) {

    $created = GooglePlace::create([
        'client_id'     => $clientId,
        'name'          => $place['title'] ?? null,
        'category'      => $place['type'] ?? null,
        'address'       => $place['address'] ?? null,
        'phone'         => $place['phone'] ?? null,
        'website'       => $place['website'] ?? null,
        'rating'        => $place['rating'] ?? null,
        'reviews_count' => $place['reviews'] ?? null,
    ]);

    if ($created->website) {

        Artisan::call('scrape:run', [
            'url' => $created->website,
            '--client' => $clientId,
        ]);

        $created->update([
            'website_scraped' => true,
            'website_scraped_at' => now(),
        ]);
    }
}


    return redirect()->route('client.google')
        ->with('success', 'Scraping Google + Web terminé');
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

public function exportExcel()
{
    $clientId = session('client.id');

    $places = GooglePlace::where('client_id', $clientId)
        ->orderByDesc('rating')
        ->get();

    $fileName = 'google-maps-entreprises.xlsx';
    $filePath = storage_path('app/' . $fileName);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $headers = [
        'Entreprise',
        'Catégorie',
        'Adresse',
        'Téléphone',
        'Site Web',
        'Note',
        'Nombre d\'avis',
        'Website Scrappé'
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    // Data
    $rowNumber = 2;

    foreach ($places as $p) {
        $sheet->setCellValue('A' . $rowNumber, $p->name);
        $sheet->setCellValue('B' . $rowNumber, $p->category);
        $sheet->setCellValue('C' . $rowNumber, $p->address);
        $sheet->setCellValue('D' . $rowNumber, $p->phone);
        $sheet->setCellValue('E' . $rowNumber, $p->website);
        $sheet->setCellValue('F' . $rowNumber, $p->rating);
        $sheet->setCellValue('G' . $rowNumber, $p->reviews_count);
        $sheet->setCellValue('H' . $rowNumber, $p->website_scraped ? 'Oui' : 'Non');
        $rowNumber++;
    }

    // Auto width
    foreach (range('A','H') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

}
