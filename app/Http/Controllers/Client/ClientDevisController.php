<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientInvoice;

class ClientDevisController extends Controller
{
    public function index()
    {
        $clients = ClientInvoice::orderBy('company_name')->get();

        $devis = [
            [
                'id' => 1,
                'client' => 'Entreprise ABC',
                'montant' => '1200€',
                'statut' => 'En attente'
            ],
        ];

        return view('client.invoice.devis', compact('clients', 'devis'));
    }
}