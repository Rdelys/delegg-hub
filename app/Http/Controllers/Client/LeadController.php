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
    public function index(Request $request)
{
    $clientId = session('client.id');

    $baseQuery = Lead::where('client_id', $clientId);

    /*
    |--------------------------------------------------------------------------
    | FILTRES
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {
        $search = $request->search;

        $baseQuery->where(function ($q) use ($search) {
            $q->where('prenom_nom', 'like', "%{$search}%")
              ->orWhere('nom_global', 'like', "%{$search}%")
              ->orWhere('entreprise', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('portable', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $baseQuery->where('status', $request->status);
    }

    if ($request->filled('chaleur')) {
        $baseQuery->where('chaleur', $request->chaleur);
    }

    if ($request->filled('date_from')) {
        $baseQuery->whereDate('date_statut', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $baseQuery->whereDate('date_statut', '<=', $request->date_to);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 STATS (SUR LES DONNÉES FILTRÉES)
    |--------------------------------------------------------------------------
    */

    $statsQuery = clone $baseQuery;

    $totalLeads = $statsQuery->count();

    $relanceCount = (clone $baseQuery)
        ->where('status', 'À relancer plus tard')
        ->count();

    $rdvPrisCount = (clone $baseQuery)
        ->where('status', 'RDV pris')
        ->count();

    $clotureCount = (clone $baseQuery)
        ->where('status', 'Clôturé')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | LISTE PAGINÉE
    |--------------------------------------------------------------------------
    */

    $leads = $baseQuery
        ->latest()
        ->paginate(20)
        ->withQueryString();

    return view('client.crm.leads', compact(
        'leads',
        'totalLeads',
        'relanceCount',
        'rdvPrisCount',
        'clotureCount'
    ));
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
