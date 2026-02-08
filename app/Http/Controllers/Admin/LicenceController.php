<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Licence;
use App\Mail\LicenceActivatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LicenceController extends Controller
{
    /**
     * Liste des licences
     */
    public function index()
    {
        return view('admin.pages.licences', [
            'clients'  => Client::orderBy('company')->get(),
            'licences' => Licence::with('client')->latest()->get(),
        ]);
    }

    /**
     * Création licence
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        // Création licence
        $licence = Licence::create([
            'client_id'  => $request->client_id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        // Calcul automatique du statut
        $licence->refreshStatus();

        // Activation du client
        $client = $licence->client;
        $client->update([
            'status' => 'actif',
        ]);

        // 📧 Email UNIQUEMENT à l’ajout
        if ($client->email) {
            Mail::to($client->email)
                ->send(new LicenceActivatedMail($client, $licence));
        }

        return redirect()
            ->back()
            ->with('success', 'Licence ajoutée et client activé');
    }

    /**
     * Suppression licence
     */
    public function destroy(Licence $licence)
    {
        $client = $licence->client;

        // Suppression licence
        $licence->delete();

        // Vérifier s’il reste une licence active
        $hasActiveLicence = $client->licences()
            ->where('status', 'actif')
            ->exists();

        // Si plus aucune licence active → client inactif
        if (!$hasActiveLicence) {
            $client->update([
                'status' => 'inactif',
            ]);
        }

        // ❌ PAS D’EMAIL ICI
        return redirect()
            ->back()
            ->with('success', 'Licence supprimée');
    }
}
