@extends('client.layouts.app')

@section('title', 'Invoice - Devis')

@section('content')

<div class="card container">

    <div class="header">
        <h2>Gestion des devis</h2>
        <p>Crée et gère facilement tes devis</p>
    </div>

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
                    <th class="text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($clients ?? [] as $client)
                <tr>
                    <td class="client-cell">
                        <div class="avatar">
                            {{ strtoupper(substr($client->company_name ?? $client->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <strong>
                                {{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}
                            </strong>
                        </div>
                    </td>

                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone }}</td>

                    <td class="text-right">
                        <button class="btn-create"
                            onclick="openModal('{{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}')">
                            + Créer devis
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
                    <td><strong>{{ $d['montant'] }}</strong></td>
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

<!-- ================= MODAL ================= -->
<!-- ================= MODAL ================= -->
<div id="modal" class="modal">
    <div class="modal-box">

        <div class="modal-header">
            <h3>Créer un devis</h3>
            <span onclick="closeModal()">✕</span>
        </div>

        <div class="modal-body">

            <!-- LIBELLE -->
            <div class="form-group">
                <label>
                    Libellé du devis
                    <span class="info" title="Nom du devis visible sur le document">i</span>
                </label>
                <input type="text" class="input">
            </div>

            <!-- DATES -->
            <div class="form-row">
                <div class="form-group">
                    <label>
                        Date d'émission *
                        <span class="info" title="Date de création du devis">i</span>
                    </label>
                    <input type="date" class="input">
                </div>

                <div class="form-group">
                    <label>
                        Date fin validité *
                        <span class="info" title="Date limite d'acceptation du devis">i</span>
                    </label>
                    <input type="date" class="input">
                </div>
            </div>

            <!-- CHAMP LIBRE -->
            <div class="form-group">
                <label>
                    Champ libre
                    <span class="info" title="Texte personnalisé affiché sur le devis">i</span>
                </label>
                <input type="text" class="input">
            </div>

            <!-- LIVRAISON -->
            <h4 class="section">Adresse de livraison</h4>

            <div class="form-group">
                <label>
                    Adresse
                    <span class="info" title="Adresse complète du client">i</span>
                </label>
                <input type="text" class="input">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        Ville
                        <span class="info" title="Ville de livraison">i</span>
                    </label>
                    <input type="text" class="input">
                </div>

                <div class="form-group">
                    <label>
                        Code postal
                        <span class="info" title="Code postal du client">i</span>
                    </label>
                    <input type="text" class="input">
                </div>
            </div>

            <div class="form-group">
                <label>
                    Pays
                    <span class="info" title="Pays de facturation">i</span>
                </label>
                <select class="input">
                    <option>France</option>
                    <option>Belgique</option>
                    <option>Suisse</option>
                    <option>Luxembourg</option>
                </select>
            </div>

            <!-- TVA -->
            <div class="form-group">
                <label>
                    Motif exonération TVA
                    <span class="info" title="À remplir si TVA = 0%">i</span>
                </label>
                <input type="text" class="input">
            </div>

            <!-- LIGNES -->
            <h4 class="section">Lignes</h4>

            <div id="lines-container"></div>

            <button class="btn-add" onclick="addLine()">+ Ajouter une ligne</button>

        </div>

        <div class="modal-footer">
            <button onclick="closeModal()" class="btn-light">Annuler</button>
            <button class="btn-main">Créer</button>
        </div>

    </div>
</div>

<!-- ================= JS ================= -->
<script>
function showTab(tab) {
    document.getElementById('clients').style.display = 'none';
    document.getElementById('devis').style.display = 'none';

    document.getElementById(tab).style.display = 'block';

    document.getElementById('tab-btn-clients').classList.remove('active');
    document.getElementById('tab-btn-devis').classList.remove('active');

    document.getElementById('tab-btn-' + tab).classList.add('active');
}

function openModal(name) {
    document.getElementById('modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

let i = 0;

function addLine() {
    i++;

    document.getElementById('lines-container').insertAdjacentHTML('beforeend', `
        <div class="line">

            <div class="line-header">
                Ligne ${i}
                <span onclick="this.parentElement.parentElement.remove()">✕</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Quantité <span class="info" title="Nombre d'unités">i</span></label>
                    <input type="number" class="input">
                </div>

                <div class="form-group">
                    <label>Unité <span class="info" title="Ex: heure, pièce">i</span></label>
                    <input type="text" class="input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prix HT € <span class="info" title="Prix unitaire hors taxe">i</span></label>
                    <input type="number" class="input">
                </div>

                <div class="form-group">
                    <label>TVA <span class="info" title="Taux de TVA applicable">i</span></label>
                    <select class="input">
                        <option>20%</option>
                        <option>10%</option>
                        <option>5.5%</option>
                        <option>2.1%</option>
                        <option>0%</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Libellé <span class="info" title="Description du produit/service">i</span></label>
                <input type="text" class="input">
            </div>

        </div>
    `);
}
</script>

<!-- ================= STYLE ================= -->
<style>

.container { padding: 25px; }

/* HEADER */
.header h2 { margin-bottom:5px; }
.header p { color:#6b7280; font-size:14px; }

/* TABS */
.tabs { display:flex; gap:10px; margin:20px 0; }
.tab-btn {
    padding:8px 16px;
    border-radius:6px;
    background:#f3f4f6;
    border:none;
    cursor:pointer;
}
.tab-btn.active { background:#111827; color:white; }

/* TABLE */
.table {
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
}
.table th {
    text-transform:uppercase;
    font-size:12px;
    padding:12px;
    background:#f9fafb;
    color:#6b7280;
}
.table td {
    padding:14px;
    border-top:1px solid #eee;
}
.table tr:hover { background:#f9fafb; }

.client-cell {
    display:flex;
    align-items:center;
    gap:10px;
}

.avatar {
    width:32px;
    height:32px;
    border-radius:50%;
    background:#111827;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
}

.text-right { text-align:right; }

/* BUTTON */
.btn-create {
    background:#4f46e5;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:6px;
    cursor:pointer;
}
.btn-create:hover { background:#4338ca; }

/* BADGE */
.badge {
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}
.success { background:#d1fae5; color:#065f46; }
.pending { background:#fef3c7; color:#92400e; }

/* MODAL */
.modal {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
}

.modal-box {
    width:700px;
    background:white;
    border-radius:12px;
    display:flex;
    flex-direction:column;
    max-height:90vh;
}

.modal-header {
    padding:15px;
    border-bottom:1px solid #eee;
    display:flex;
    justify-content:space-between;
}

.modal-body {
    padding:15px;
    overflow:auto;
}

.modal-footer {
    padding:15px;
    border-top:1px solid #eee;
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

/* FORM */
.input {
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
    margin-top:5px;
}

.form-group { margin-bottom:10px; }
.form-row { display:flex; gap:10px; }

.section {
    margin:15px 0 10px;
}

/* LINES */
.line {
    border:1px solid #eee;
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
    background:#fafafa;
}

.line-header {
    display:flex;
    justify-content:space-between;
    margin-bottom:8px;
    font-size:13px;
}

/* BUTTONS */
.btn-add {
    background:#e5e7eb;
    padding:8px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.btn-main {
    background:#111827;
    color:white;
    padding:8px 14px;
    border:none;
    border-radius:6px;
}

.btn-light {
    background:#e5e7eb;
    padding:8px 14px;
    border:none;
    border-radius:6px;
}

/* INFO ICON */
.info {
    display: inline-block;
    margin-left: 6px;
    width: 16px;
    height: 16px;
    font-size: 11px;
    text-align: center;
    line-height: 16px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #374151;
    cursor: pointer;
    position: relative;
}

/* TOOLTIP */
.info:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 130%;
    left: 50%;
    transform: translateX(-50%);
    background: #111827;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 10;
}

.info:hover::before {
    content: "";
    position: absolute;
    bottom: 115%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: #111827;
}
</style>

@endsection