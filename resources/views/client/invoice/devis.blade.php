@extends('client.layouts.app')

@section('title', 'Invoice - Devis')

@section('content')

<div class="card">

    <h2 class="title">Gestion des devis</h2>

    <!-- TABS -->
    <div class="tabs">
        <button onclick="showTab('clients')" id="tab-btn-clients" class="tab-btn active">Clients</button>
        <button onclick="showTab('devis')" id="tab-btn-devis" class="tab-btn">Devis</button>
    </div>

    <!-- ================= CLIENTS ================= -->
    <div id="clients" class="tab-content">

        <table class="table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($clients ?? [] as $client)
                <tr>
                    <td>
                        <strong>
                            {{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}
                        </strong>
                    </td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone }}</td>
                    <td style="text-align:right;">
                        <button class="btn-create"
                            onclick="openModal('{{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}')">
                            + Devis
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- ================= DEVIS ================= -->
    <div id="devis" class="tab-content" style="display:none;">

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Statut</th>
                </tr>
            </thead>

            <tbody>
                @foreach($devis ?? [] as $d)
                <tr>
                    <td>#{{ $d['id'] }}</td>
                    <td>{{ $d['client'] }}</td>
                    <td>{{ $d['montant'] }}</td>
                    <td>
                        <span class="badge {{ $d['statut'] == 'Accepté' ? 'success' : 'pending' }}">
                            {{ $d['statut'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
    <div class="modal-box">
        <h3>Créer un devis</h3>
        <p id="modal-client-name"></p>

        <input type="text" placeholder="Montant (€)" class="input">

        <div class="actions">
            <button onclick="closeModal()" class="btn-light">Annuler</button>
            <button class="btn-main">Créer</button>
        </div>
    </div>
</div>

<!-- JS -->
<script>
function showTab(tab) {
    document.getElementById('clients').style.display = 'none';
    document.getElementById('devis').style.display = 'none';

    document.getElementById(tab).style.display = 'block';

    document.getElementById('tab-btn-clients').classList.remove('active');
    document.getElementById('tab-btn-devis').classList.remove('active');

    document.getElementById('tab-btn-' + tab).classList.add('active');
}

function openModal(clientName) {
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('modal-client-name').innerText = clientName;
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}
</script>

<!-- STYLE -->
<style>

.title {
    margin-bottom: 20px;
}

/* TABS */
.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.tab-btn {
    padding: 8px 16px;
    border-radius: 6px;
    background: #f3f4f6;
    border: none;
    cursor: pointer;
}

.tab-btn.active {
    background: #111827;
    color: white;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.table th {
    text-align: left;
    font-size: 13px;
    padding: 12px;
    background: #f9fafb;
    color: #6b7280;
}

.table td {
    padding: 12px;
    border-top: 1px solid #eee;
}

.table tr:hover {
    background: #f9fafb;
}

/* BUTTON */
.btn-create {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-create:hover {
    background: #4338ca;
}

/* BADGE */
.badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.success {
    background: #d1fae5;
    color: #065f46;
}

.pending {
    background: #fef3c7;
    color: #92400e;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
}

.modal-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
}

.input {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.actions {
    margin-top: 15px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-main {
    background: #111827;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
}

.btn-light {
    background: #e5e7eb;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
}

</style>

@endsection