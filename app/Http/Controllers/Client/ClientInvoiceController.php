<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientInvoice;
use Illuminate\Support\Facades\Http;

class ClientInvoiceController extends Controller
{

    // LISTE
    public function index()
    {
        $clients = ClientInvoice::latest()->get();

        return view('client.invoice.clients', compact('clients'));
    }

function convertCountryToISO($country)
{
    $map = [
        'France' => 'FR',
        'Suisse' => 'CH',
        'Belgique' => 'BE',
        'Luxembourg' => 'LU',
    ];

    return $map[$country] ?? $country;
}

function formatPhone($phone, $countryISO)
{
    if (!$phone) return null;

    // enlever espaces et 0 au début
    $phone = preg_replace('/\s+/', '', $phone);
    $phone = ltrim($phone, '0');

    $prefixes = [
        'FR' => '+33',
        'BE' => '+32',
        'CH' => '+41',
        'LU' => '+352',
    ];

    return isset($prefixes[$countryISO])
        ? $prefixes[$countryISO] . $phone
        : $phone;
}
    // STORE
    public function store(Request $request)
    {

        $data = $request->validate([
    'type'=>'required',

    'company_name'=>'nullable',
    'siret'=>'nullable',
    'tva'=>'nullable',

    'first_name'=>'nullable',
    'last_name'=>'nullable',

    'email'=>'required|email',
    'phone'=>'nullable',

    'address'=>'required',
    'address_complement'=>'nullable',
    'postal_code'=>'required',
    'city'=>'required',
    'country'=>'required',

    'iban'=>'nullable',
    'bic'=>'nullable',

    'include_address'=>'nullable',

    'notes'=>'nullable'
]);

$data['country'] = $this->convertCountryToISO($request->country);
$data['phone'] = $this->formatPhone($request->phone, $data['country']);

ClientInvoice::create($data);

        return back()->with('success','Client ajouté');
    }

// Récupérer les données d'un client pour l'édition
public function edit($id)
{
    $client = ClientInvoice::findOrFail($id);
    return response()->json($client);
}
    // UPDATE
    public function update(Request $request,$id)
    {

        $client = ClientInvoice::findOrFail($id);

$data = $request->validate([

'type'=>'required',

'company_name'=>'nullable',
'siret'=>'nullable',
'tva'=>'nullable',

'first_name'=>'nullable',
'last_name'=>'nullable',

'email'=>'required|email',
'phone'=>'nullable',


'address'=>'required',
'address_complement'=>'nullable',
'postal_code'=>'required',
'city'=>'required',
'country'=>'required',

'iban'=>'nullable',
'bic'=>'nullable',

'include_address'=>'nullable',
]);


$data['country'] = $this->convertCountryToISO($request->country);
$data['phone'] = $this->formatPhone($request->phone, $data['country']);

$client->update($data);

        return back()->with('success','Client modifié');
    }


    // DELETE
    public function destroy($id)
    {

        $client = ClientInvoice::findOrFail($id);

        $client->delete();

        return back()->with('success','Client supprimé');
    }

    public function syncTiime(Request $request)
{
    $webhookUrl = "https://hook.eu2.make.com/pbiaah4c1p2mrufjqqtway92nrm4vruw";

    $ids = $request->client_ids
        ? explode(',', $request->client_ids)
        : [];

    // ❌ AUCUNE SÉLECTION → STOP
    if (empty($ids)) {
        return back()->with('error', 'Veuillez sélectionner au moins un client');
    }

    // ✅ Récupérer uniquement les sélectionnés NON exportés
    $clients = ClientInvoice::whereIn('id', $ids)
        ->where('exported_to_tiime', false)
        ->get();

    if ($clients->isEmpty()) {
        return back()->with('error', 'Tous les clients sélectionnés sont déjà exportés');
    }

    // 🔥 Détection type
    $hasParticulier = $clients->contains('type', 'particulier');

    // Envoi webhook
    Http::post($webhookUrl, [
        'action' => 'sync_tiime',
        'mode' => $hasParticulier ? 'particulier' : 'professionnel',
        'clients' => $clients
    ]);

    // ✅ Marquer comme exporté
    ClientInvoice::whereIn('id', $clients->pluck('id'))
        ->update(['exported_to_tiime' => true]);

    return back()->with('success', $clients->count().' client(s) exporté(s)');
}
}