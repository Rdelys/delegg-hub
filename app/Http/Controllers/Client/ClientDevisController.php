<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientInvoice;
use App\Models\Devis;
use Illuminate\Http\Request;

class ClientDevisController extends Controller
{
    public function index()
    {
        $clients = ClientInvoice::orderBy('company_name')->get();
        $devis = Devis::with('client')->latest()->get();

        return view('client.invoice.devis', compact('clients', 'devis'));
    }

    public function store(Request $request)
    {
        $client = ClientInvoice::findOrFail($request->client_id);

        $totalHT = 0;
        $totalTVA = 0;

        foreach ($request->lines as $line) {

            $lineTotal = $line['quantity'] * $line['price_ht'];
            $tvaAmount = $lineTotal * ($line['tva'] / 100);

            $totalHT += $lineTotal;
            $totalTVA += $tvaAmount;
        }

        Devis::create([
            'client_id' => $client->id,
            'tiime_id' => $client->tiime_id,

            'label' => $request->label,
            'date_emission' => $request->date_emission,
            'date_validite' => $request->date_validite,
            'note' => $request->note,

            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,

            'tva_exoneration' => $request->tva_exoneration,

            // 🔥 ON STOCKE TOUT
            'lines' => $request->lines,

            'total_ht' => $totalHT,
            'total_tva' => $totalTVA,
            'total_ttc' => $totalHT + $totalTVA
        ]);

        return back()->with('success', 'Devis créé');
    }

    public function update(Request $request, $id)
    {
        $devis = Devis::findOrFail($id);

        $totalHT = 0;
        $totalTVA = 0;

        foreach ($request->lines as $line) {
            $lineTotal = $line['quantity'] * $line['price_ht'];
            $tvaAmount = $lineTotal * ($line['tva'] / 100);
            $totalHT += $lineTotal;
            $totalTVA += $tvaAmount;
        }

        $devis->update([
            'label'          => $request->label,
            'date_emission'  => $request->date_emission,
            'date_validite'  => $request->date_validite,
            'note'           => $request->note,
            'address'        => $request->address,
            'city'           => $request->city,
            'postal_code'    => $request->postal_code,
            'country'        => $request->country,
            'tva_exoneration'=> $request->tva_exoneration,
            'lines'          => $request->lines,
            'total_ht'       => $totalHT,
            'total_tva'      => $totalTVA,
            'total_ttc'      => $totalHT + $totalTVA,
        ]);

        return back()->with('success', 'Devis mis à jour');
    }

    public function destroy($id)
    {
        try {
            $devis = Devis::findOrFail($id);
            $devis->delete();
            
            return redirect()->route('client.invoice.devis')
                ->with('success', 'Devis supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('client.invoice.devis')
                ->with('error', 'Erreur lors de la suppression du devis');
        }
    }
}