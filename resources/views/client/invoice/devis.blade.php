@extends('client.layouts.app')

@section('title', 'Invoice - Devis')

@section('content')

<div class="card">
    <h2>Devis</h2>

    <!-- Onglets -->
    <div style="margin-bottom: 20px;">
        <button onclick="showTab('clients')" class="tab-btn">Clients</button>
        <button onclick="showTab('devis')" class="tab-btn">Devis</button>
    </div>

    <!-- TAB CLIENTS -->
    <div id="clients" class="tab-content">
        <h3>Liste des clients</h3>

        @foreach($clients as $client)
            <div class="item">
                <strong>
                    {{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}
                </strong><br>
                {{ $client->email }}<br>
                {{ $client->phone }}
            </div>
        @endforeach
    </div>

    <!-- TAB DEVIS -->
    <div id="devis" class="tab-content" style="display:none;">
        <h3>Liste des devis</h3>

        @foreach($devis as $d)
            <div class="item">
                <strong>Devis #{{ $d['id'] }}</strong><br>
                Client : {{ $d['client'] }}<br>
                Montant : {{ $d['montant'] }}<br>
                Statut : {{ $d['statut'] }}
            </div>
        @endforeach
    </div>
</div>

<script>
function showTab(tab) {
    document.getElementById('clients').style.display = 'none';
    document.getElementById('devis').style.display = 'none';

    document.getElementById(tab).style.display = 'block';
}
</script>

<style>
.tab-btn {
    padding: 8px 15px;
    margin-right: 10px;
    cursor: pointer;
}

.item {
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
}
</style>

@endsection