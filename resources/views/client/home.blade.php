@extends('client.layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="card">
    <h1>Bienvenue sur DELEGG HUB</h1>

    <p style="margin-top:15px">
        Bonjour <strong>{{ session('client.first_name') }}</strong>,<br>
        vous êtes connecté à l’espace professionnel de
        <strong>{{ session('client.company') }}</strong>.
    </p>
</div>
@endsection
