<?php
// app/Http/Controllers/GoogleScraperController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GooglePlace;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
            'api_key' => config('services.serpapi.key'),
        ]);

        if (!$response->ok()) {
            return back()->with('success', 'Erreur SerpAPI');
        }

        $results = $response->json('local_results');

        if (!$results) {
            return back()->with('success', 'Aucun résultat');
        }

        foreach ($results as $place) {
            // Créer ou mettre à jour l'entrée Google Place
            $googlePlace = GooglePlace::updateOrCreate(
                [
                    'client_id' => $clientId,
                    'website' => $place['website'] ?? null,
                    'name' => $place['title'] ?? null,
                ],
                [
                    'category' => $place['type'] ?? null,
                    'address' => $place['address'] ?? null,
                    'phone' => $place['phone'] ?? null,
                    'rating' => $place['rating'] ?? null,
                    'reviews_count' => $place['reviews'] ?? null,
                ]
            );

            // Si un site web existe, lancer le scraping des contacts
            if ($googlePlace->website && !$googlePlace->contact_scraped_at) {
                try {
                    Artisan::call('scrape:website', [
                        'google_place_id' => $googlePlace->id,
                        'url' => $googlePlace->website,
                        '--client' => $clientId,
                    ]);

                    Log::info('Scraping website lancé pour: ' . $googlePlace->website);
                } catch (\Exception $e) {
                    Log::error('Erreur scraping website: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('client.google')
            ->with('success', 'Scraping Google + Web terminé');
    }

    public function exportPdf()
    {
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

        // Data
        $rowNumber = 2;

        foreach ($places as $p) {
            $sheet->setCellValue('A' . $rowNumber, $p->name);
            $sheet->setCellValue('B' . $rowNumber, $p->category);
            $sheet->setCellValue('C' . $rowNumber, $p->address);
            $sheet->setCellValue('D' . $rowNumber, $p->phone);
            $sheet->setCellValue('E' . $rowNumber, $p->website);
            $sheet->setCellValue('F' . $rowNumber, $p->email);
            $sheet->setCellValue('G' . $rowNumber, $p->facebook);
            $sheet->setCellValue('H' . $rowNumber, $p->instagram);
            $sheet->setCellValue('I' . $rowNumber, $p->linkedin);
            $sheet->setCellValue('J' . $rowNumber, $p->rating);
            $sheet->setCellValue('K' . $rowNumber, $p->reviews_count);
            $sheet->setCellValue('L' . $rowNumber, $p->website_scraped ? 'Oui' : 'Non');
            $sheet->setCellValue('M' . $rowNumber, $p->contact_scraped_at ? 'Oui' : 'Non');
            $rowNumber++;
        }

        // Auto width
        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}