@extends('client.layouts.app')

@section('title', 'Invoice - Devis')

@section('content')

<div class="card container">

    <div class="header">
        <h2><i class="fas fa-file-invoice-dollar"></i> Gestion des devis</h2>
        <p><i class="fas fa-magic"></i> Créez et gérez facilement vos devis en un clin d'œil</p>
    </div>

    <!-- TABS -->
    <div class="tabs">
        <button onclick="showTab('clients')" id="tab-btn-clients" class="tab-btn active">
            <i class="fas fa-users"></i> Clients
        </button>
        <button onclick="showTab('devis')" id="tab-btn-devis" class="tab-btn">
            <i class="fas fa-file-alt"></i> Devis
        </button>
    </div>

    <!-- ================= CLIENTS ================= -->
    <div id="clients" class="tab-content">

        <table class="table">
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Client</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-phone"></i> Téléphone</th>
                    <th class="text-right"><i class="fas fa-cog"></i> Action</th>
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

                    <td class="email-cell">{{ $client->email }}</td>
                    <td class="phone-cell">{{ $client->phone }}</td>

                    <td class="text-right">
                        <button class="btn-create"
                            onclick="openModal('{{ $client->company_name }}', {{ $client->id }})">
                            <i class="fas fa-plus-circle"></i> + Créer un devis
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

   <!-- ================= DEVIS ================= -->
    <div id="devis" class="tab-content" style="display:none;">
        <div class="devis-toolbar">
            <label class="select-all-label">
                <input type="checkbox" id="sel-all" onchange="toggleAll(this)">
                Tout sélectionner
            </label>

            <button class="btn-tiime" id="btn-tiime" disabled onclick="exportTiime()">
                <i class="fas fa-upload"></i>
                Exporter vers Tiime
                <span class="tiime-badge hidden" id="tiime-badge">0</span>
            </button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width:40px"></th> 
                    <th><i class="fas fa-hashtag"></i> #</th>
                    <th><i class="fas fa-user"></i> Client</th>
                    <th><i class="fas fa-calendar-alt"></i> Date</th>
                    <th><i class="fas fa-euro-sign"></i> Montant TTC</th>
                    <th><i class="fas fa-cog"></i> Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($devis as $d)
                <tr>
                    <td>
                        <input type="checkbox" class="row-cb" data-id="{{ $d->id }}" onchange="updateSelection()">
                    </td>
                    <td class="devis-id">#{{ $d->id }}</td>
                    <td class="client-name">
                        {{ $d->client->company_name 
                            ?? $d->client->first_name . ' ' . $d->client->last_name }}
                    </td>
                    <td class="devis-date">
                        {{ \Carbon\Carbon::parse($d->date_emission)->format('d/m/Y') }}
                    </td>
                    <td class="devis-amount">
                        <strong>{{ number_format($d->total_ttc, 2, ',', ' ') }} €</strong>
                    </td>
                    <td class="text-right">
                        <button class="btn-create"
                            onclick="openEditModal({{ json_encode($d) }})">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <button class="btn-delete"
                            onclick="deleteDevis({{ $d->id }})">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
                    </td>
                    <!-- ================= FORMULAIRE DE SUPPRESSION ================= -->
                    <form id="delete-form" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="fas fa-inbox"></i> Aucun devis pour le moment
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

<!-- ================= MODAL ================= -->
<div id="modal" class="modal">
    <div class="modal-box">

        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> Créer un nouveau devis</h3>
            <span class="close-icon" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </span>
        </div>

        <form method="POST" action="{{ route('client.invoice.devis.store') }}">
        @csrf

        <div class="modal-body">

            <input type="hidden" name="client_id" id="client_id">

            <!-- LIBELLE -->
            <div class="form-group">
                <label>
                    <i class="fas fa-tag"></i> Libellé du devis
                    <span class="info" title="Nom du devis visible sur le document">
                        <i class="fas fa-info-circle"></i>
                    </span>
                </label>
                <input type="text" name="label" class="input" placeholder="Ex: Devis prestation 2025">
            </div>

            <!-- DATES -->
            <div class="form-row">
                <div class="form-group">
                    <label>
                        <i class="fas fa-calendar-check"></i> Date d'émission *
                        <span class="info" title="Date de création du devis">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    </label>
                    <input type="date" name="date_emission" class="input" required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-hourglass-end"></i> Date fin validité *
                        <span class="info" title="Date limite d'acceptation du devis">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    </label>
                    <input type="date" name="date_validite" class="input" required>
                </div>
            </div>

            <!-- CHAMP LIBRE -->
            <div class="form-group">
                <label>
                    <i class="fas fa-comment"></i> Champ libre
                    <span class="info" title="Texte personnalisé affiché sur le devis">
                        <i class="fas fa-info-circle"></i>
                    </span>
                </label>
                <input type="text" name="note" class="input" placeholder="Informations complémentaires...">
            </div>

            <!-- LIVRAISON -->
            <h4 class="section">
                <i class="fas fa-truck"></i> Adresse de livraison
            </h4>

            <div class="form-group">
                <label>
                    <i class="fas fa-home"></i> Adresse
                    <span class="info" title="Adresse complète du client">
                        <i class="fas fa-info-circle"></i>
                    </span>
                </label>
                <input type="text" name="address" class="input" placeholder="Numéro et rue">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <i class="fas fa-city"></i> Ville
                        <span class="info" title="Ville de livraison">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    </label>
                    <input type="text" name="city" class="input" placeholder="Ville">
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-mail-bulk"></i> Code postal
                        <span class="info" title="Code postal du client">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    </label>
                    <input type="text" name="postal_code" class="input" placeholder="Code postal">
                </div>
            </div>

            <!-- COUNTRY ISO -->
            <div class="form-group">
                <label>
                    <i class="fas fa-globe"></i> Pays
                    <span class="info" title="Pays de facturation">
                        <i class="fas fa-info-circle"></i>
                    </span>
                </label>
                <select name="country" class="input">
                    <option value="FR"><i class="fas fa-flag-fr"></i> France</option>
                    <option value="BE"><i class="fas fa-flag-be"></i> Belgique</option>
                    <option value="CH"><i class="fas fa-flag-ch"></i> Suisse</option>
                    <option value="LU"><i class="fas fa-flag-lu"></i> Luxembourg</option>
                </select>
            </div>

            <!-- TVA -->
            <div class="form-group">
                <label>
                    <i class="fas fa-percent"></i> Motif exonération TVA
                    <span class="info" title="À remplir si TVA = 0%">
                        <i class="fas fa-info-circle"></i>
                    </span>
                </label>
                <input type="text" name="tva_exoneration" class="input" placeholder="Ex: Auto-entrepreneur, exonération article 293 B du CGI">
            </div>

            <!-- LIGNES -->
            <h4 class="section">
                <i class="fas fa-list-ul"></i> Lignes du devis
            </h4>

            <div id="lines-container"></div>

            <button type="button" class="btn-add" onclick="addLine(event)">
                <i class="fas fa-plus"></i> Ajouter une ligne
            </button>

        </div>

        <div class="modal-footer">
            <button type="button" onclick="closeModal()" class="btn-light">
                <i class="fas fa-times"></i> Annuler
            </button>

            <button type="submit" class="btn-main">
                <i class="fas fa-check-circle"></i> Créer le devis
            </button>
        </div>

        </form>

    </div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div id="modal-edit" class="modal">
    <div class="modal-box">

        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Modifier le devis</h3>
            <span class="close-icon" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </span>
        </div>

        <form method="POST" id="edit-form" action="">
        @csrf
        @method('PUT')

        <div class="modal-body">

            <input type="hidden" name="client_id" id="edit_client_id">

            <div class="form-group">
                <label><i class="fas fa-tag"></i> Libellé du devis</label>
                <input type="text" name="label" id="edit_label" class="input">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-calendar-check"></i> Date d'émission *</label>
                    <input type="date" name="date_emission" id="edit_date_emission" class="input" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hourglass-end"></i> Date fin validité *</label>
                    <input type="date" name="date_validite" id="edit_date_validite" class="input" required>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-comment"></i> Champ libre</label>
                <input type="text" name="note" id="edit_note" class="input">
            </div>

            <h4 class="section"><i class="fas fa-truck"></i> Adresse de livraison</h4>

            <div class="form-group">
                <label><i class="fas fa-home"></i> Adresse</label>
                <input type="text" name="address" id="edit_address" class="input">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-city"></i> Ville</label>
                    <input type="text" name="city" id="edit_city" class="input">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-mail-bulk"></i> Code postal</label>
                    <input type="text" name="postal_code" id="edit_postal_code" class="input">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-globe"></i> Pays</label>
                <select name="country" id="edit_country" class="input">
                    <option value="FR">France</option>
                    <option value="BE">Belgique</option>
                    <option value="CH">Suisse</option>
                    <option value="LU">Luxembourg</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-percent"></i> Motif exonération TVA</label>
                <input type="text" name="tva_exoneration" id="edit_tva_exoneration" class="input">
            </div>

            <h4 class="section"><i class="fas fa-list-ul"></i> Lignes du devis</h4>

            <div id="edit-lines-container"></div>

            <button type="button" class="btn-add" onclick="addEditLine(event)">
                <i class="fas fa-plus"></i> Ajouter une ligne
            </button>

        </div>

        <div class="modal-footer">
            <button type="button" onclick="closeEditModal()" class="btn-light">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="submit" class="btn-main">
                <i class="fas fa-check-circle"></i> Enregistrer
            </button>
        </div>

        </form>
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

function openModal(name, clientId) {
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('client_id').value = clientId;
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

let i = 0;

function addLine(e) {
    if (e) e.preventDefault();

    i++;

    document.getElementById('lines-container').insertAdjacentHTML('beforeend', `
        <div class="line">

            <div class="line-header">
                <i class="fas fa-cube"></i> Ligne ${i}
                <span class="remove-line" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash-alt"></i>
                </span>
            </div>

            <div class="form-row">
                <input type="number" name="lines[${i}][quantity]" placeholder="Quantité" class="input" required>
                <input type="text" name="lines[${i}][unit]" placeholder="Unité (pièce, heure, m...)" class="input">
            </div>

            <div class="form-row">
                <input type="number" step="0.01" name="lines[${i}][price_ht]" placeholder="Prix HT" class="input" required>

                <select name="lines[${i}][tva]" class="input">
                    <option value="20">20%</option>
                    <option value="10">10%</option>
                    <option value="5.5">5.5%</option>
                    <option value="2.1">2.1%</option>
                    <option value="0">0%</option>
                </select>
            </div>

            <input type="text" name="lines[${i}][label]" placeholder="Libellé du produit/service" class="input" required>

        </div>
    `);
}

let editLineIndex = 0;

function openEditModal(devis) {
    // Remplir le formulaire
    document.getElementById('edit_client_id').value   = devis.client_id;
    document.getElementById('edit_label').value        = devis.label ?? '';
    document.getElementById('edit_date_emission').value= devis.date_emission ?? '';
    document.getElementById('edit_date_validite').value= devis.date_validite ?? '';
    document.getElementById('edit_note').value         = devis.note ?? '';
    document.getElementById('edit_address').value      = devis.address ?? '';
    document.getElementById('edit_city').value         = devis.city ?? '';
    document.getElementById('edit_postal_code').value  = devis.postal_code ?? '';
    document.getElementById('edit_country').value      = devis.country ?? 'FR';
    document.getElementById('edit_tva_exoneration').value = devis.tva_exoneration ?? '';

    // Action du formulaire
    document.getElementById('edit-form').action =
        '/invoice/devis/' + devis.id;

    // Lignes existantes
    const container = document.getElementById('edit-lines-container');
    container.innerHTML = '';
    editLineIndex = 0;

    const lines = typeof devis.lines === 'string'
        ? JSON.parse(devis.lines)
        : (devis.lines ?? []);

    // lines est un objet { "1": {...}, "2": {...} } ou un tableau
    const linesArray = Array.isArray(lines) ? lines : Object.values(lines);

    linesArray.forEach(line => addEditLine(null, line));

    document.getElementById('modal-edit').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('modal-edit').style.display = 'none';
}

function addEditLine(e, data = {}) {
    if (e) e.preventDefault();
    editLineIndex++;

    const tvaOptions = ['20', '10', '5.5', '2.1', '0'].map(v =>
        `<option value="${v}" ${String(data.tva) === v ? 'selected' : ''}>${v}%</option>`
    ).join('');

    document.getElementById('edit-lines-container').insertAdjacentHTML('beforeend', `
        <div class="line">
            <div class="line-header">
                <i class="fas fa-cube"></i> Ligne ${editLineIndex}
                <span class="remove-line" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash-alt"></i>
                </span>
            </div>
            <div class="form-row">
                <input type="number" name="lines[${editLineIndex}][quantity]"
                    placeholder="Quantité" class="input" value="${data.quantity ?? ''}" required>
                <input type="text" name="lines[${editLineIndex}][unit]"
                    placeholder="Unité" class="input" value="${data.unit ?? ''}">
            </div>
            <div class="form-row">
                <input type="number" step="0.01" name="lines[${editLineIndex}][price_ht]"
                    placeholder="Prix HT" class="input" value="${data.price_ht ?? ''}" required>
                <select name="lines[${editLineIndex}][tva]" class="input">
                    ${tvaOptions}
                </select>
            </div>
            <input type="text" name="lines[${editLineIndex}][label]"
                placeholder="Libellé du produit/service" class="input"
                value="${data.label ?? ''}" required>
        </div>
    `);
}

function deleteDevis(devisId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce devis ? Cette action est irréversible.')) {
        const form = document.getElementById('delete-form');
        form.action = '/invoice/devis/' + devisId;
        form.submit();
    }
}
function updateSelection() {
    const cbs = document.querySelectorAll('.row-cb');
    const checked = [...cbs].filter(c => c.checked);
    const n = checked.length;

    const btn = document.getElementById('btn-tiime');
    const badge = document.getElementById('tiime-badge');
    const selAll = document.getElementById('sel-all');

    btn.disabled = n === 0;
    badge.textContent = n;
    badge.classList.toggle('hidden', n === 0);

    selAll.indeterminate = n > 0 && n < cbs.length;
    selAll.checked = n === cbs.length;

    document.querySelectorAll('#devis tbody tr').forEach(tr => {
        const cb = tr.querySelector('.row-cb');
        tr.classList.toggle('selected', cb && cb.checked);
    });
}

function toggleAll(el) {
    document.querySelectorAll('.row-cb').forEach(c => { c.checked = el.checked; });
    updateSelection();
}

function exportTiime() {
    const ids = [...document.querySelectorAll('.row-cb:checked')].map(c => c.dataset.id);
    const n = ids.length;

    if (confirm(`Exporter ${n} devis vers Tiime ?`)) {
        alert(`✓ ${n} devis exporté${n > 1 ? 's' : ''} vers Tiime avec succès.\n\nIDs : ${ids.join(', ')}`);
        
        // Décocher après export
        document.querySelectorAll('.row-cb').forEach(c => c.checked = false);
        document.getElementById('sel-all').checked = false;
        updateSelection();
    }
}
</script>

<!-- ================= STYLE ================= -->
<style>

* {
    box-sizing: border-box;
}

.devis-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 10px;
}

.select-all-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #64748b;
    cursor: pointer;
    user-select: none;
}

.btn-tiime {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    background: #1a1a2e;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-tiime:disabled { opacity: 0.35; cursor: not-allowed; }
.btn-tiime:not(:disabled):hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(26, 26, 46, 0.3);
}

.tiime-badge {
    background: #4f46e5;
    color: #fff;
    border-radius: 20px;
    padding: 2px 7px;
    font-size: 11px;
    font-weight: 600;
    transition: all 0.2s;
}

.tiime-badge.hidden { display: none; }

#devis tbody tr.selected td { background: rgba(79, 70, 229, 0.04); }

.btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    margin-left: 8px;
}

.btn-delete:hover { 
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Responsive pour les petits écrans */
@media (max-width: 640px) {
    .btn-create, .btn-delete {
        font-size: 11px;
        padding: 6px 10px;
        margin-left: 4px;
    }
}

.container { 
    padding: 25px; 
    background: #f8fafc;
    min-height: 100vh;
}

/* HEADER */
.header h2 { 
    margin-bottom: 5px; 
    font-size: 28px;
    font-weight: 600;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header h2 i {
    -webkit-text-fill-color: initial;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.header p { 
    color: #64748b; 
    font-size: 14px; 
    font-weight: 400;
}

.header p i {
    color: #4f46e5;
    margin-right: 5px;
}

/* TABS */
.tabs { 
    display: flex; 
    gap: 12px; 
    margin: 24px 0 20px 0; 
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 0;
}

.tab-btn {
    padding: 10px 20px;
    border-radius: 8px 8px 0 0;
    background: transparent;
    border: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    color: #64748b;
    transition: all 0.2s ease;
}

.tab-btn i {
    margin-right: 8px;
}

.tab-btn:hover {
    color: #4f46e5;
    background: #f1f5f9;
}

.tab-btn.active { 
    background: #ffffff;
    color: #4f46e5;
    border-bottom: 2px solid #4f46e5;
    margin-bottom: -2px;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.table th {
    text-transform: uppercase;
    font-size: 12px;
    padding: 14px 16px;
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-align: left;
}

.table th i {
    margin-right: 6px;
    font-size: 12px;
    color: #4f46e5;
}

.table td {
    padding: 16px;
    border-top: 1px solid #f1f5f9;
    color: #334155;
}

.table tr:hover { 
    background: #fafbff; 
    transition: background 0.2s ease;
}

.client-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    flex-shrink: 0;
}

.email-cell, .phone-cell {
    color: #475569;
}

.devis-id {
    font-weight: 600;
    color: #4f46e5;
}

.devis-amount {
    font-weight: 600;
    color: #059669;
}

.client-name {
    font-weight: 500;
}

.text-right { 
    text-align: right; 
}

.empty-state {
    text-align: center;
    padding: 40px !important;
    color: #94a3b8;
    font-size: 14px;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 10px;
    display: block;
}

/* BUTTON */
.btn-create {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.btn-create:hover { 
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

/* BADGE */
.badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.success { background: #d1fae5; color: #065f46; }
.pending { background: #fef3c7; color: #92400e; }

/* MODAL - SCROLLABLE */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
}

.modal-box {
    width: 100%;
    max-width: 800px;
    background: white;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.02);
    animation: modalSlideIn 0.2s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border-radius: 16px 16px 0 0;
    flex-shrink: 0;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
}

.modal-header h3 i {
    color: #4f46e5;
    margin-right: 8px;
}

.close-icon {
    cursor: pointer;
    font-size: 18px;
    color: #94a3b8;
    transition: color 0.2s ease;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.close-icon:hover {
    color: #ef4444;
    background: #fef2f2;
}

/* MODAL BODY - SCROLLABLE */
.modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
    max-height: calc(90vh - 130px);
}

/* Custom scrollbar */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #fafbfc;
    border-radius: 0 0 16px 16px;
    flex-shrink: 0;
}

/* FORM */
.input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-top: 6px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-group { 
    margin-bottom: 16px; 
}

.form-group label {
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-row { 
    display: flex; 
    gap: 16px; 
}

.form-row .form-group {
    flex: 1;
}

.section {
    margin: 20px 0 12px 0;
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    border-left: 3px solid #4f46e5;
    padding-left: 12px;
}

.section i {
    color: #4f46e5;
    margin-right: 6px;
}

/* LINES */
.line {
    border: 1px solid #e2e8f0;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 16px;
    background: #fefefe;
    transition: all 0.2s ease;
}

.line:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.line-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 13px;
    font-weight: 600;
    color: #4f46e5;
}

.line-header i {
    margin-right: 6px;
}

.remove-line {
    cursor: pointer;
    color: #94a3b8;
    transition: color 0.2s ease;
    padding: 4px 8px;
    border-radius: 6px;
}

.remove-line:hover {
    color: #ef4444;
    background: #fef2f2;
}

/* BUTTONS */
.btn-add {
    background: #f1f5f9;
    padding: 10px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    transition: all 0.2s ease;
    width: 100%;
}

.btn-add i {
    margin-right: 6px;
}

.btn-add:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}

.btn-main {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-main i {
    margin-right: 6px;
}

.btn-main:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-light {
    background: white;
    color: #475569;
    padding: 10px 20px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-light i {
    margin-right: 6px;
}

.btn-light:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

/* INFO ICON */
.info {
    display: inline-flex;
    margin-left: 6px;
    width: 18px;
    height: 18px;
    font-size: 11px;
    text-align: center;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #e2e8f0;
    color: #64748b;
    cursor: pointer;
    position: relative;
    font-style: normal;
    font-weight: bold;
    transition: all 0.2s ease;
}

.info i {
    font-size: 10px;
    margin: 0;
}

.info:hover {
    background: #cbd5e1;
    color: #475569;
}

/* TOOLTIP */
.info:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 130%;
    left: 50%;
    transform: translateX(-50%);
    background: #1e293b;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 10;
    font-weight: normal;
}

.info:hover::before {
    content: "";
    position: absolute;
    bottom: 115%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: #1e293b;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .modal-box {
        max-width: 95%;
        margin: 10px;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .table {
        font-size: 13px;
    }
    
    .table th, .table td {
        padding: 10px;
    }
    
    .client-cell {
        flex-direction: column;
        text-align: center;
    }
    
    .btn-create {
        font-size: 11px;
        padding: 6px 10px;
    }
}

@media (max-width: 640px) {
    .modal-body {
        padding: 16px;
    }
    
    .modal-header, .modal-footer {
        padding: 16px;
    }
}
</style>

@endsection