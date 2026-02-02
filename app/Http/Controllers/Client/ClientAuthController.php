<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    // Page login
    public function showLogin()
    {
        return view('client.login');
    }

    // Traitement login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)->first();

        // Email ou mot de passe incorrect
        if (!$client || !Hash::check($request->password, $client->password)) {
            return back()->with('error', 'Identifiants incorrects');
        }

        // Compte inactif
        if ($client->status !== 'actif') {
            return back()->with('error', 'Votre compte est désactivé');
        }

        // Stockage en session
        session([
            'client_logged' => true,
            'client' => [
                'id'         => $client->id,
                'first_name' => $client->first_name,
                'last_name'  => $client->last_name,
                'email'      => $client->email,
                'role'       => $client->role,
                'company'       => $client->company,

            ]
        ]);

        return redirect()->route('client.home');
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->forget('client');
        $request->session()->forget('client_logged');

        return redirect()->route('client.login');
    }
}
