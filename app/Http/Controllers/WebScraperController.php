<?php

namespace App\Http\Controllers;

use App\Models\ScrapedContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\GooglePlace;

class WebScraperController extends Controller
{
   public function index()
{
    $clientId = session('client.id');

    $results = ScrapedContact::where('client_id', $clientId)
        ->latest()
        ->paginate(10);

    $websites = GooglePlace::where('client_id', $clientId)
        ->whereNotNull('website')
        ->pluck('website');

    return view('client.web', compact('results', 'websites'));
}


    public function scrape(Request $request)
{
    $request->validate([
        'url' => 'nullable|url',
        'website_select' => 'nullable|url'
    ]);

$url = $request->url;

    if (!$url) {
        return back()->withErrors(['url' => 'Veuillez saisir ou sélectionner une URL']);
    }

    Artisan::call('scrape:run', [
        'url' => $url,
        '--client' => session('client.id'),
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

public function deleteSelected(Request $request)
{
    $clientId = session('client.id');

    $request->validate([
        'selected' => 'required|array'
    ]);

    \App\Models\ScrapedContact::where('client_id', $clientId)
        ->whereIn('id', $request->selected)
        ->delete();

    return redirect()
        ->route('client.web')
        ->with('success', 'Éléments supprimés avec succès');
}

public function exportExcel()
{
    $clientId = session('client.id');

    $results = ScrapedContact::where('client_id', $clientId)
        ->latest()
        ->get();

    $fileName = 'web-scraper-results.xlsx';
    $filePath = storage_path('app/' . $fileName);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // En-têtes
    $headers = [
        'Nom',
        'Email',
        'Facebook',
        'Instagram',
        'LinkedIn',
        'Source'
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    // Données
    $rowNumber = 2;

    foreach ($results as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row->name);
        $sheet->setCellValue('B' . $rowNumber, $row->email);
        $sheet->setCellValue('C' . $rowNumber, $row->facebook);
        $sheet->setCellValue('D' . $rowNumber, $row->instagram);
        $sheet->setCellValue('E' . $rowNumber, $row->linkedin);
        $sheet->setCellValue('F' . $rowNumber, $row->source_url);
        $rowNumber++;
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

}

