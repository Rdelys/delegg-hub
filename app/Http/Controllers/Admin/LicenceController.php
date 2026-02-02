<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;

class LicenceController extends Controller
{
    /**
     * Page licences
     * - entreprises dynamiques depuis clients
     * - licences encore statiques
     */
    public function index()
    {
        return view('admin.pages.licences', [
            // pour le select entreprise
            'clients' => Client::orderBy('company')->get(),

            // tableau licences (statique pour l’instant)
            'licences' => [
                [
                    'id' => 1,
                    'company' => 'Tech Solutions',
                    'start_date' => '2025-01-01',
                    'end_date' => '2025-12-31',
                    'status' => 'active',
                ],
                [
                    'id' => 2,
                    'company' => 'WebPro',
                    'start_date' => '2024-06-01',
                    'end_date' => '2025-06-01',
                    'status' => 'expired',
                ],
            ]
        ]);
    }
}
