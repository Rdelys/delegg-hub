@extends('client.layouts.app')

@section('title', 'Mon profil')

@section('content')

@if(session('success'))
    <div class="card" style="border-left:4px solid #22c55e;margin-bottom:20px">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #ef4444;margin-bottom:20px">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <h2>Informations personnelles</h2>

    <form method="POST" action="{{ route('client.profil.update') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

            <div>
                <label>Prénom</label>
                <input type="text" name="first_name" value="{{ session('client.first_name') }}" required>
            </div>

            <div>
                <label>Nom</label>
                <input type="text" name="last_name" value="{{ session('client.last_name') }}" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ session('client.email') }}" required>
            </div>

            <div>
                <label>Téléphone</label>
                <input type="text" name="phone" value="{{ session('client.phone') }}" required>
            </div>

            <div style="grid-column:1/3">
                <label>Adresse</label>
                <input type="text" name="address" value="{{ session('client.address') }}" required>
            </div>

            <div style="grid-column:1/3">
                <label>Entreprise</label>
                <input type="text" name="company" value="{{ session('client.company') }}" required>
            </div>

        </div>

        <button style="margin-top:20px" type="submit">
            <i class="fa-solid fa-save"></i> Enregistrer
        </button>
    </form>
</div>

<div class="card" style="margin-top:30px">
    <h2>Changer le mot de passe</h2>

    <form method="POST" action="{{ route('client.profil.password') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

            <div style="grid-column:1/3">
                <label>Mot de passe actuel</label>
                <input type="password" name="current_password" required>
            </div>

            <div>
                <label>Nouveau mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <div>
                <label>Confirmation</label>
                <input type="password" name="password_confirmation" required>
            </div>

        </div>

        <button style="margin-top:20px" type="submit">
            <i class="fa-solid fa-lock"></i> Modifier le mot de passe
        </button>
    </form>
</div>

<style>
    label {
        display:block;
        font-size:14px;
        margin-bottom:6px;
        color:#475569;
    }

    input {
        width:100%;
        padding:12px;
        border-radius:10px;
        border:1px solid #cbd5f5;
        background:#f8fafc;
    }

    input:focus {
        outline:none;
        border-color:#4f46e5;
        background:#fff;
    }

    button {
        padding:12px 20px;
        border-radius:10px;
        border:none;
        background:#4f46e5;
        color:white;
        font-weight:600;
        cursor:pointer;
    }

    button:hover {
        background:#4338ca;
    }
</style>

@endsection
