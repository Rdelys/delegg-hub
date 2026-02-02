<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientProfileController extends Controller
{
    public function index()
    {
        return view('client.profil');
    }

    public function update(Request $request)
    {
        $client = Client::findOrFail(session('client.id'));

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:clients,email,' . $client->id,
            'phone'      => 'nullable|string|max:30',
            'address'    => 'nullable|string|max:255',
            'company'    => 'required|string|max:150',
        ]);

        $client->update($request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
            'company',
        ]));

        // Mise à jour session
        session()->put('client', array_merge(
            session('client'),
            $request->only(['first_name', 'last_name', 'email', 'company'])
        ));

        return back()->with('success', 'Profil mis à jour avec succès');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $client = Client::findOrFail(session('client.id'));

        if (!Hash::check($request->current_password, $client->password)) {
            return back()->with('error', 'Mot de passe actuel incorrect');
        }

        $client->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe modifié avec succès');
    }
}
