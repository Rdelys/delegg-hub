<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE DES LEADS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $clientId = session('client.id');

        $leads = Lead::where('client_id', $clientId)
            ->latest()
            ->paginate(20);

        return view('client.crm.leads', compact('leads'));
    }

    /*
    |--------------------------------------------------------------------------
    | AJOUTER UN LEAD
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $clientId = session('client.id');

        $data = $this->validateLead($request);

        $data['client_id'] = $clientId;
        $data['follow_insta'] = $request->has('follow_insta');

        Lead::create($data);

        return redirect()
            ->route('client.crm.leads')
            ->with('success', 'Lead ajouté avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | MODIFIER UN LEAD
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Lead $lead)
    {
        $clientId = session('client.id');

        // Sécurité
        if ($lead->client_id !== $clientId) {
            abort(403);
        }

        $data = $this->validateLead($request);

        $data['follow_insta'] = $request->has('follow_insta');

        $lead->update($data);

        return redirect()
            ->route('client.crm.leads')
            ->with('success', 'Lead modifié avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER
    |--------------------------------------------------------------------------
    */
    public function destroy(Lead $lead)
    {
        $clientId = session('client.id');

        if ($lead->client_id !== $clientId) {
            abort(403);
        }

        $lead->delete();

        return redirect()
            ->route('client.crm.leads')
            ->with('success', 'Lead supprimé');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION CENTRALISÉE
    |--------------------------------------------------------------------------
    */
    private function validateLead(Request $request)
    {
        return $request->validate([

            // Identité
            'prenom_nom'       => 'required|string|max:255',
            'nom_global'       => 'nullable|string|max:255',
            'commentaire'      => 'nullable|string',

            // Pipeline
            'chaleur'          => 'nullable|string|max:50',
            'status'           => 'nullable|string|max:255',
            'status_relance'   => 'nullable|string|max:255',
            'enfants_percent'  => 'nullable|string|max:10',
            'date_statut'      => 'nullable|date',

            // Canaux
            'linkedin_status'  => 'nullable|string|max:255',
            'suivi_mail'       => 'nullable|string|max:255',
            'suivi_whatsapp'   => 'nullable|string|max:255',
            'appel_tel'        => 'nullable|string|max:255',
            'mp_instagram'     => 'nullable|string|max:255',
            'com_instagram'    => 'nullable|string|max:255',
            'formulaire_site'  => 'nullable|string|max:255',
            'messenger'        => 'nullable|string|max:255',

            // Entreprise
            'entreprise'       => 'nullable|string|max:255',
            'fonction'         => 'nullable|string|max:255',

            // Contact
            'email'            => 'nullable|email|max:255',
            'tel_fixe'         => 'nullable|string|max:50',
            'portable'         => 'nullable|string|max:50',

            // URL
            'url_linkedin'     => 'nullable|string|max:255',
            'url_maps'         => 'nullable|string|max:255',
            'url_site'         => 'nullable|string|max:255',
            'compte_insta'     => 'nullable|string|max:255',

            // Commercial
            'devis'            => 'nullable|string|max:50',
        ]);
    }
}
