<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ClientUserController extends Controller
{
    private function authorizeSuperAdmin()
    {
        if (session('client.role') !== 'superadmin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();

        $users = Client::where('company', session('client.company'))->get();

        return view('client.utilisateurs', compact('users'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:clients,email',
            'role'       => 'required|in:superadmin,simpleutilisateur',
        ]);

        $password = Str::random(10);

        $user = Client::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'company'    => session('client.company'),
            'password'   => Hash::make($password),
            'role'       => $request->role,
            'status'     => 'actif',
        ]);

        // ENVOI MAIL (simple, extensible)
        Mail::raw(
            "Bonjour {$user->first_name},\n\nVotre accès Delegg Hub a été créé.\n\nEmail : {$user->email}\nMot de passe : {$password}",
            fn ($message) => $message->to($user->email)->subject('Accès à Delegg Hub')
        );

        return back()->with('success', 'Utilisateur créé et email envoyé');
    }

    public function update(Request $request, Client $client)
    {
        $this->authorizeSuperAdmin();

        if ($client->company !== session('client.company')) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'role'       => 'required|in:superadmin,simpleutilisateur',
        ]);

        $client->update($request->only('first_name', 'last_name', 'role'));

        return back()->with('success', 'Utilisateur modifié');
    }

    public function destroy(Client $client)
    {
        $this->authorizeSuperAdmin();

        if ($client->company !== session('client.company')) {
            abort(403);
        }

        // Empêcher suppression de soi-même
        if ($client->id === session('client.id')) {
            return back()->with('error', 'Impossible de supprimer votre propre compte');
        }

        $client->delete();

        return back()->with('success', 'Utilisateur supprimé');
    }
}
