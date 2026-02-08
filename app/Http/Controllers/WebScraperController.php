<?php

namespace App\Http\Controllers;

use App\Models\ScrapedContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class WebScraperController extends Controller
{
    public function index()
    {
        $results = ScrapedContact::latest()->get();
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
}

