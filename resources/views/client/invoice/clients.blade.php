    @extends('client.layouts.app')

    @section('title', 'Gestion des clients')

    @section('content')
    <div class="clients-container">
        {{-- EN-TÊTE --}}
        <div class="page-header">
            <h2 class="page-title">Clients</h2>
            <div class="header-actions">
                <button class="btn btn-success" type="button" onclick="openModal('addClientModal')">
                    <i class="fas fa-plus"></i> Ajouter un client
                </button>
                <form id="exportForm" action="{{ route('tiime.sync') }}" method="POST">
                    @csrf
                    <input type="hidden" name="client_ids" id="client_ids">
                    <button id="exportBtn" class="btn btn-sync" type="button" onclick="submitExport()">
                        <i class="fas fa-sync-alt"></i> Exporter professionnels
                    </button>
                </form>
            </div>
        </div>

        {{-- BARRE D'ACTIONS DE SÉLECTION --}}
        <div class="selection-bar" id="selectionBar" style="display: none;">
            <div class="selection-info">
                <span id="selectedCount">0</span> client(s) sélectionné(s)
            </div>
            <div class="selection-actions">
                <button class="btn btn-outline" type="button" onclick="selectAll()">
                    <i class="fas fa-check-double"></i> Tout sélectionner
                </button>
                <button class="btn btn-outline" type="button" onclick="deselectAll()">
                    <i class="fas fa-times"></i> Tout désélectionner
                </button>
            </div>
        </div>

        {{-- TABLEAU DES CLIENTS --}}
        <div class="card">
            <div class="table-responsive">
                <table class="clients-table">
                    <thead>
                        <tr>
                            <th class="checkbox-column">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllCheckboxes(this)">
                                    <span class="checkbox-custom"></span>
                                </label>
                            </th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Ville</th>
                            <th>Type</th>
                            <th>Exporté</th>
                            <th>Exporté après modification</th>
                            <th class="actions-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
    <tr data-client-id="{{ $client->id }}" onclick="openViewClientModal({{ $client->id }})" style="cursor:pointer;">
                                <td class="checkbox-column">
                                <label class="checkbox-label">
                                    <input type="checkbox"
                                        class="client-checkbox"
                                        value="{{ $client->id }}"
                                        data-type="{{ $client->type }}"
                                        data-exported="{{ $client->exported_to_tiime }}"
                                        onclick="handleCheckboxClick(event)">
                                    <span class="checkbox-custom"></span>
                                </label>
                            </td>
                            <td>
                                <div class="company-cell">
                                    @if($client->type == 'professionnel')
                                        <span class="company-name">
                                            {{ $client->company_name }}
                                        </span>
                                        @if($client->siret)
                                            <small class="company-siret">
                                                SIRET: {{ $client->siret }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="company-name">
                                            {{ $client->first_name }} {{ $client->last_name }}
                                        </span>
                                        <small class="company-siret">
                                            Particulier
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>{{ $client->city }}</td>
                            <td>
                                @if($client->type == 'professionnel')
                                    <span class="badge badge-pro">Professionnel</span>
                                @else
                                    <span class="badge badge-part">Particulier</span>
                                @endif
                            </td>
                            <td>
                                @if($client->exported_to_tiime)
                                    <span class="badge badge-success">Oui</span>
                                @else
                                    <span class="badge badge-warning">Non</span>
                                @endif
                            </td>
                            <td>
                                @if($client->exported_after_update)
                                    <span class="badge badge-success">Oui</span>
                                @else
                                    <span class="badge badge-warning">Non</span>
                                @endif
                            </td>
                            <td class="actions-cell">
                                <button
                                class="btn-icon btn-edit"
                                onclick="event.stopPropagation(); openEditModal({{ $client->id }})"
                                title="Modifier"
                                type="button">
                                <i class="fas fa-edit"></i>
                                </button>
                                <form
                                    action="{{ route('clients.delete', $client->id) }}"
                                    method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                    class="btn-icon btn-delete"
                                    title="Supprimer"
                                    type="submit"
                                    onclick="event.stopPropagation(); return confirmDelete(event,'Supprimer ce client ?')">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL AJOUT CLIENT --}}
        <div class="modal" id="addClientModal">
            <div class="modal-overlay" onclick="closeModal('addClientModal')"></div>
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus"></i> Nouveau client
                        </h5>
                        <button type="button" class="btn-close" onclick="closeModal('addClientModal')">&times;</button>
                    </div>

                    <div class="modal-body">
                        {{-- TABS --}}
                        <div class="nav-tabs">
                            <button class="nav-link active" type="button">Informations</button>
                        </div>

                        <form id="addClientForm" method="POST" action="{{ route('clients.store') }}">
                            @csrf
                            {{-- TAB INFORMATIONS --}}
                            <div class="tab-pane active" id="add-info-tab">
                                <div class="form-section">
                                    <h6 class="section-title">Type de client</h6>
                                    <div class="client-type-group">
                                        <label class="radio-label">
                                            <input type="radio" name="type" value="professionnel" checked onchange="toggleClientTypeFields('add')">
                                            <span class="radio-custom"></span>
                                            Professionnel
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" name="type" value="particulier" onchange="toggleClientTypeFields('add')">
                                            <span class="radio-custom"></span>
                                            Particulier
                                        </label>
                                    </div>
                                </div>

                                {{-- Champs pour Professionnel --}}
                                <div id="add-professionnel-fields">
                                    <div class="form-section">
                                        <h6 class="section-title">Informations de l'entreprise</h6>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="company_name" placeholder="Nom de l'entreprise *">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="siret" placeholder="N° de SIREN ou SIRET">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="tva" placeholder="N° de TVA intracom">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="Mail *" required>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="form-group" style="max-width:120px;">
                                                        <select class="phonePrefix" class="form-control" style="max-width:120px;">
                                                            <option value="+33">🇫🇷 +33</option>
                                                            <option value="+32">🇧🇪 +32</option>
                                                            <option value="+41">🇨🇭 +41</option>
                                                            <option value="+352">🇱🇺 +352</option>
                                                        </select>                                                </div>
                                                    <div class="form-group">
                                                        <input type="tel" id="phoneInput" class="form-control" name="phone" placeholder="612345678">
                                                    </div>
                                                </div>                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Champs pour Particulier --}}
                                <div id="add-particulier-fields" style="display: none;">
                                    <div class="form-section">
                                        <h6 class="section-title">Informations personnelles</h6>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="last_name" placeholder="Nom *">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="first_name" placeholder="Prénom *">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="Mail *" required>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="form-group" style="max-width:120px;">
                                                        <select class="phonePrefix" class="form-control" style="max-width:120px;">
                                                            <option value="+33">🇫🇷 +33</option>
                                                            <option value="+32">🇧🇪 +32</option>
                                                            <option value="+41">🇨🇭 +41</option>
                                                            <option value="+352">🇱🇺 +352</option>
                                                        </select>                                                </div>
                                                    <div class="form-group">
                                                        <input type="tel" id="phoneInput" class="form-control" name="phone" placeholder="612345678">
                                                    </div>
                                                </div>                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Toggle d'inclusion d'adresse --}}
                                <div class="form-section">
                                    <div class="toggle-container">
                                        <label class="toggle-label">
                                            <input type="checkbox" class="toggle-checkbox" id="addIncludeAddress" name="include_address" value="1" checked>
                                            <span class="toggle-switch"></span>
                                            <span class="toggle-text">Inclure cette adresse lors des envois par mail</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h6 class="section-title">Adresse</h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="address" placeholder="Adresse *" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="address_complement" placeholder="Complément d'adresse">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="postal_code" placeholder="Code postal *" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="city" placeholder="Ville *" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <select name="country" id="addCountry" class="form-control" required onchange="updatePhonePrefix(this.value)">
                                            <option value="France">France</option>
                                            <option value="Belgique">Belgique</option>
                                            <option value="Suisse">Suisse</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-section collapsible">
                                    <div class="collapsible-header" type="button" onclick="toggleBankSection('add')">
                                        <span>Informations bancaires</span>
                                        <span class="arrow" id="add-bank-arrow">▼</span>
                                    </div>
                                    <div class="collapsible-body" id="add-bank-fields" style="display: none;">
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="iban" placeholder="IBAN">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="bic" placeholder="BIC">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('addClientModal')">
                            Annuler
                        </button>
                        <button type="button" class="btn btn-success" onclick="document.getElementById('addClientForm').submit();">
                            <i class="fas fa-save"></i> Ajouter le client
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL MODIFICATION CLIENT --}}
        <div class="modal" id="editClientModal">
            <div class="modal-overlay" onclick="closeModal('editClientModal')"></div>
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-user-edit"></i> Modifier le client
                        </h5>
                        <button type="button" class="btn-close" onclick="closeModal('editClientModal')">&times;</button>
                    </div>

                    <div class="modal-body">
                        {{-- TABS --}}
                        <div class="nav-tabs">
                            <button class="nav-link active" type="button" onclick="switchTab('edit', 'info', event)">Informations</button>
                        </div>

                        <form id="editClientForm" method="POST" action="">
                            @csrf
                            <input type="hidden" name="id" id="editClientId">
                            
                            {{-- TAB INFORMATIONS --}}
                            <div class="tab-pane active" id="edit-info-tab">
                                <div class="form-section">
                                    <h6 class="section-title">Type de client</h6>
                                    <div class="client-type-group">
                                        <label class="radio-label">
                                            <input type="radio" name="type" value="professionnel" id="editTypePro" onchange="toggleClientTypeFields('edit')">
                                            <span class="radio-custom"></span>
                                            Professionnel
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" name="type" value="particulier" id="editTypePart" onchange="toggleClientTypeFields('edit')">
                                            <span class="radio-custom"></span>
                                            Particulier
                                        </label>
                                    </div>
                                </div>

                                {{-- Champs pour Professionnel --}}
                                <div id="edit-professionnel-fields">
                                    <div class="form-section">
                                        <h6 class="section-title">Informations de l'entreprise</h6>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="company_name" id="editCompanyName" placeholder="Nom de l'entreprise *">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="siret" id="editSiret" placeholder="N° de SIREN ou SIRET">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="tva" id="editTva" placeholder="N° de TVA intracom">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" id="editEmail" placeholder="Mail *">
                                            </div>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="form-group" style="max-width:120px;">
                                                        <select class="phonePrefix" class="form-control" style="max-width:120px;">
                                                            <option value="+33">🇫🇷 +33</option>
                                                            <option value="+32">🇧🇪 +32</option>
                                                            <option value="+41">🇨🇭 +41</option>
                                                            <option value="+352">🇱🇺 +352</option>
                                                        </select>                                                
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="tel" id="editPhone" class="form-control" name="phone">  
                                                    </div>
                                                </div>                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Champs pour Particulier --}}
                                <div id="edit-particulier-fields" style="display: none;">
                                    <div class="form-section">
                                        <h6 class="section-title">Informations personnelles</h6>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="last_name" id="editLastName" placeholder="Nom *">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="first_name" id="editFirstName" placeholder="Prénom *">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" id="editEmailParticulier" placeholder="Mail *">
                                            </div>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="form-group" style="max-width:120px;">
                                                        <select class="phonePrefix" class="form-control" style="max-width:120px;">
                                                            <option value="+33">🇫🇷 +33</option>
                                                            <option value="+32">🇧🇪 +32</option>
                                                            <option value="+41">🇨🇭 +41</option>
                                                            <option value="+352">🇱🇺 +352</option>
                                                        </select>                                                
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="tel" class="form-control" id="editPhoneParticulier" name="phone" placeholder="612345678">
                                                    </div>
                                                </div>                                       
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Toggle d'inclusion d'adresse --}}
                                <div class="form-section">
                                    <div class="toggle-container">
                                        <label class="toggle-label">
                                            <input type="checkbox" class="toggle-checkbox" name="include_address" id="editIncludeAddress" value="1">
                                            <span class="toggle-switch"></span>
                                            <span class="toggle-text">Inclure cette adresse lors des envois par mail</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h6 class="section-title">Adresse</h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="address" id="editAddress" placeholder="Adresse *">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="address_complement" id="editAddressComplement" placeholder="Complément d'adresse">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="postal_code" id="editPostalCode" placeholder="Code postal *">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="city" id="editCity" placeholder="Ville *">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <select name="country" class="form-control" id="editCountry" required onchange="updatePhonePrefix(this.value)">
                                            <option value="France">France</option>
                                            <option value="Belgique">Belgique</option>
                                            <option value="Suisse">Suisse</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-section collapsible">
                                    <div class="collapsible-header" type="button" onclick="toggleBankSection('edit')">
                                        <span>Informations bancaires</span>
                                        <span class="arrow" id="edit-bank-arrow">▼</span>
                                    </div>
                                    <div class="collapsible-body" id="edit-bank-fields" style="display: none;">
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="iban" id="editIban" placeholder="IBAN">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="bic" id="editBic" placeholder="BIC">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('editClientModal')">
                            Annuler
                        </button>
                        <button type="button" class="btn btn-success" onclick="saveClientChanges()">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="viewClientModal">

    <div class="modal-overlay" onclick="closeModal('viewClientModal')"></div>

    <div class="modal-dialog modal-xl">
    <div class="modal-content">

    <div class="modal-header">
    <h5 class="modal-title">
    <i class="fas fa-user"></i> Détails du client
    </h5>

    <button class="btn-close" onclick="closeModal('viewClientModal')">&times;</button>
    </div>

    <div class="modal-body">

    <div class="form-section">
    <h6 class="section-title">Informations générales</h6>

    <p><strong>Type :</strong> <span id="viewType"></span></p>

    <!-- PROFESSIONNEL -->
    <div class="form-section">
    <!-- PROFESSIONNEL -->
    <div id="view-pro-fields">

    <p><strong>Entreprise :</strong> <span id="viewCompany"></span></p>
    <p><strong>SIRET :</strong> <span id="viewSiret"></span></p>
    <p><strong>TVA :</strong> <span id="viewTva"></span></p>

    </div>

    <!-- PARTICULIER -->
    <div id="view-part-fields" style="display:none">

    <p><strong>Nom :</strong> <span id="viewLastName"></span></p>
    <p><strong>Prénom :</strong> <span id="viewFirstName"></span></p>

    </div>

    <p><strong>Email :</strong> <span id="viewEmail"></span></p>
    <p><strong>Téléphone :</strong> <span id="viewPhone"></span></p>

    </div>


    <div class="form-section">
    <h6 class="section-title">Adresse</h6>

    <p><strong>Adresse :</strong> <span id="viewAddress"></span></p>
    <p><strong>Complément :</strong> <span id="viewAddressComplement"></span></p>
    <p><strong>Code postal :</strong> <span id="viewPostal"></span></p>
    <p><strong>Ville :</strong> <span id="viewCity"></span></p>
    <p><strong>Pays :</strong> <span id="viewCountry"></span></p>

    </div>



    <div class="form-section">
    <h6 class="section-title">Informations bancaires</h6>

    <p><strong>IBAN :</strong> <span id="viewIban"></span></p>
    <p><strong>BIC :</strong> <span id="viewBic"></span></p>

    </div>

    </div>


    <div class="modal-footer">

    <button class="btn btn-outline"
    onclick="closeModal('viewClientModal')">
    Fermer
    </button>

    <button class="btn btn-success"
    onclick="closeModal('viewClientModal'); openEditModal(currentEditClientId)">
    Modifier
    </button>

    </div>

    </div>
    </div>
    </div>
    </div>

    <style>
    /* RESET */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* VARIABLES */
    :root {
        --primary: #3b82f6;
        --success: #22c55e;
        --success-dark: #16a34a;
        --danger: #ef4444;
        --warning: #f59e0b;
        --text-dark: #0f172a;
        --text-medium: #334155;
        --text-light: #64748b;
        --text-lighter: #94a3b8;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --border-light: #e2e8f0;
        --border-lighter: #f1f5f9;
        --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* LAYOUT */
    .clients-container {
        padding: 24px;
        background-color: var(--bg-light);
        min-height: 100vh;
    }

    /* HEADER */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* SELECTION BAR */
    .selection-bar {
        background: var(--primary);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .selection-info {
        font-weight: 500;
    }

    .selection-actions {
        display: flex;
        gap: 8px;
    }

    .selection-actions .btn-outline {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .selection-actions .btn-outline:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* BADGES */
    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-pro {
        background: rgba(59, 130, 246, 0.1);
        color: var(--primary);
    }

    .badge-part {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success-dark);
    }

    /* BUTTONS */
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: inherit;
    }

    .btn i {
        font-size: 1rem;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), var(--success-dark));
        color: white;
        box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.2);
    }

    .btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px -1px rgba(34, 197, 94, 0.3);
    }

    .btn-success:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-export {
        background: var(--bg-white);
        color: var(--text-light);
        border: 1px solid var(--border-light);
    }

    .btn-export:hover:not(:disabled) {
        background: var(--bg-light);
        border-color: var(--text-lighter);
    }

    .btn-export:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-sync {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-sync:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border-light);
        color: var(--text-light);
    }

    .btn-outline:hover {
        background: var(--border-lighter);
    }

    /* CARD */
    .card {
        background: var(--bg-white);
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    /* TABLE */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .clients-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .clients-table th {
        background: var(--bg-light);
        padding: 16px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-light);
        text-align: left;
        border-bottom: 2px solid var(--border-light);
    }

    .clients-table td {
        padding: 16px 20px;
        color: var(--text-medium);
        border-bottom: 1px solid var(--border-lighter);
    }

    .clients-table tr:hover td {
        background: var(--bg-light);
    }

    /* CHECKBOX COLUMN */
    .checkbox-column {
        width: 50px;
        text-align: center;
    }

    .checkbox-label {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        position: relative;
    }

    .checkbox-label input[type="checkbox"] {
        display: none;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-light);
        border-radius: 4px;
        display: inline-block;
        position: relative;
        transition: all 0.2s ease;
    }

    .checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
        background: var(--primary);
        border-color: var(--primary);
    }

    .checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
        content: '✓';
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
    }

    .checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
        animation: checkPulse 0.3s ease;
    }

    @keyframes checkPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .company-cell {
        display: flex;
        flex-direction: column;
    }

    .company-name {
        font-weight: 500;
        color: var(--text-dark);
    }

    .company-siret {
        font-size: 0.75rem;
        color: var(--text-light);
        margin-top: 2px;
    }

    .actions-column {
        width: 100px;
        text-align: right;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        color: var(--text-light);
        font-size: 1rem;
    }

    .btn-icon:hover {
        background: var(--border-lighter);
        transform: translateY(-2px);
    }

    .btn-edit:hover {
        color: var(--primary);
        background: rgba(59, 130, 246, 0.1);
    }

    .btn-delete:hover {
        color: var(--danger);
        background: rgba(239, 68, 68, 0.1);
    }

    /* MODAL */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
    }

    .modal.show {
        display: block;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .badge-success {
        background: rgba(34,197,94,0.1);
        color: #16a34a;
    }

    .badge-warning {
        background: rgba(245,158,11,0.1);
        color: #f59e0b;
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 28px auto;
        max-width: 500px;
        animation: slideIn 0.3s ease;
    }

    .modal-dialog.modal-xl {
        max-width: 1140px;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-content {
        position: relative;
        background-color: var(--bg-white);
        border: none;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-lighter);
        background: var(--bg-light);
        border-radius: 20px 20px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        line-height: 1;
        color: var(--text-light);
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-close:hover {
        background: var(--border-light);
        color: var(--text-dark);
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--border-lighter);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    /* TABS */
    .nav-tabs {
        display: flex;
        gap: 4px;
        border-bottom: 2px solid var(--border-light);
        margin-bottom: 24px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 2px;
    }

    .nav-link {
        border: none;
        padding: 12px 24px;
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-light);
        background: transparent;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s ease;
        cursor: pointer;
        font-family: inherit;
        white-space: nowrap;
    }

    .nav-link:hover {
        color: var(--primary);
        background: rgba(59, 130, 246, 0.05);
    }

    .nav-link.active {
        color: var(--primary);
        border-bottom: 2px solid var(--primary);
        margin-bottom: -2px;
    }

    /* TAB PANES */
    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* FORMS */
    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-medium);
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border-light);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-header .section-title {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .form-row {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-row:last-child {
        margin-bottom: 0;
    }

    .form-group {
        margin-bottom: 16px;
        flex: 1;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: var(--bg-white);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-lighter);
    }

    /* RADIO BUTTONS */
    .client-type-group {
        display: flex;
        gap: 24px;
        background: var(--bg-light);
        padding: 12px 16px;
        border-radius: 10px;
        flex-wrap: wrap;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 0.95rem;
        color: var(--text-medium);
        position: relative;
    }

    .radio-label input[type="radio"] {
        display: none;
    }

    .radio-custom {
        width: 18px;
        height: 18px;
        border: 2px solid var(--text-lighter);
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }

    .radio-label input[type="radio"]:checked + .radio-custom {
        border-color: var(--success);
    }

    .radio-label input[type="radio"]:checked + .radio-custom::after {
        content: '';
        width: 10px;
        height: 10px;
        background: var(--success);
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* TOGGLE SWITCH */
    .toggle-container {
        background: var(--bg-light);
        padding: 16px;
        border-radius: 10px;
        border: 1px solid var(--border-light);
    }

    .toggle-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
        flex-wrap: wrap;
    }

    .toggle-checkbox {
        display: none;
    }

    .toggle-switch {
        position: relative;
        width: 50px;
        height: 24px;
        background: var(--border-light);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .toggle-checkbox:checked + .toggle-switch {
        background: var(--success);
    }

    .toggle-checkbox:checked + .toggle-switch::after {
        left: 28px;
    }

    .toggle-text {
        font-size: 0.95rem;
        color: var(--text-medium);
        font-weight: 500;
    }

    /* CONTACT CARD */
    .contact-card {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--border-light);
        margin-bottom: 16px;
        position: relative;
    }

    .contact-card:last-child {
        margin-bottom: 0;
    }

    .contact-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .contact-number {
        font-weight: 600;
        color: var(--text-medium);
        font-size: 0.95rem;
    }

    .btn-add-contact {
        padding: 8px 16px;
        background: var(--success);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-add-contact:hover {
        background: var(--success-dark);
        transform: translateY(-1px);
    }

    .btn-remove-contact {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        background: transparent;
        color: var(--danger);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-remove-contact:hover {
        background: rgba(239, 68, 68, 0.1);
        transform: scale(1.1);
    }

    /* COLLAPSIBLE */
    .collapsible {
        border: 1px solid var(--border-light);
        border-radius: 10px;
        background: var(--bg-light);
    }

    .collapsible-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        font-weight: 600;
        color: var(--text-medium);
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .collapsible-header:hover {
        background: var(--border-lighter);
    }

    .collapsible-body {
        padding: 16px;
        border-top: 1px solid var(--border-light);
    }

    .arrow {
        transition: transform 0.3s ease;
    }

    /* ANIMATIONS */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .fa-spin {
        animation: spin 1s linear infinite;
    }

    /* TOAST NOTIFICATION */
    .toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        padding: 16px 24px;
        background: white;
        border-radius: 10px;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1100;
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .toast-success {
        border-left: 4px solid var(--success);
    }

    .toast-error {
        border-left: 4px solid var(--danger);
    }

    .toast-info {
        border-left: 4px solid var(--primary);
    }

    .toast-icon {
        font-size: 1.2rem;
    }

    .toast-success .toast-icon {
        color: var(--success);
    }

    .toast-error .toast-icon {
        color: var(--danger);
    }

    .toast-info .toast-icon {
        color: var(--primary);
    }

    .toast-message {
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    /* UTILS */
    .mt-2 {
        margin-top: 8px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .clients-container {
            padding: 16px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
        
        .btn {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        .form-row {
            flex-direction: column;
            gap: 12px;
        }
        
        .modal-dialog {
            margin: 10px;
        }
        
        .nav-link {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 16px;
        }
        
        .client-type-group {
            gap: 16px;
        }
        
        .selection-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .selection-actions {
            justify-content: center;
        }
        
        .toast {
            left: 16px;
            right: 16px;
            bottom: 16px;
        }
        
        .section-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.5rem;
        }
        
        .header-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .clients-table {
            min-width: 600px;
        }
        
        .modal-dialog.modal-xl {
            margin: 0;
            height: 100%;
            max-height: 100%;
            border-radius: 0;
        }
        
        .modal-content {
            border-radius: 0;
            max-height: 100%;
            height: 100%;
        }
        
        .nav-tabs {
            padding: 0 4px;
        }
        
        .nav-link {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
    }
    </style>

    <div class="modal" id="errorModal">
        <div class="modal-overlay" onclick="closeModal('errorModal')"></div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Erreur</h5>
                    <button class="btn-close" onclick="closeModal('errorModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" onclick="closeModal('errorModal')">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Variable pour stocker l'ID du client en cours d'édition
    let currentEditClientId = null;

    // Gestion des modales
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
            
            // Réinitialiser le formulaire si c'est le modal d'ajout
            if (modalId === 'addClientModal') {
                resetAddForm();
            }
        }
    }

    // Réinitialisation du formulaire d'ajout
    function resetAddForm() {
        document.getElementById('addClientForm').reset();
        document.getElementById('add-professionnel-fields').style.display = 'block';
        document.getElementById('add-particulier-fields').style.display = 'none';
        document.querySelector('input[name="type"][value="professionnel"]').checked = true;
        document.getElementById('addIncludeAddress').checked = true;
        document.getElementById('addCountry').value = 'France';
    }

    // Gestion des onglets
    function switchTab(modalType, tabName, event) {
        event.preventDefault();
        
        // Désactiver tous les onglets
        const tabs = document.querySelectorAll(`#${modalType}ClientModal .nav-link`);
        tabs.forEach(tab => tab.classList.remove('active'));
        
        // Activer l'onglet cliqué
        event.target.classList.add('active');
        
        // Cacher tous les contenus d'onglets
        const panes = document.querySelectorAll(`#${modalType}ClientModal .tab-pane`);
        panes.forEach(pane => pane.classList.remove('active'));
        
        // Afficher le contenu correspondant
        const targetPane = document.getElementById(`${modalType}-${tabName}-tab`);
        if (targetPane) {
            targetPane.classList.add('active');
        }
    }

    // Basculement des champs selon le type de client
    function toggleClientTypeFields(modalType) {

        const selected = document.querySelector(`#${modalType}ClientModal input[name="type"]:checked`);
        if (!selected) return;

        const isProfessional = selected.value === "professionnel";

        const proFields = document.getElementById(`${modalType}-professionnel-fields`);
        const partFields = document.getElementById(`${modalType}-particulier-fields`);

        const proInputs = proFields.querySelectorAll("input");
        const partInputs = partFields.querySelectorAll("input");

        if (isProfessional) {

            proFields.style.display = "block";
            partFields.style.display = "none";

            proInputs.forEach(i => i.disabled = false);
            partInputs.forEach(i => i.disabled = true);

        } else {

            proFields.style.display = "none";
            partFields.style.display = "block";

            proInputs.forEach(i => i.disabled = true);
            partInputs.forEach(i => i.disabled = false);
        }
    }

    // Gestion des sections repliables
    function toggleBankSection(type) {
        const section = document.getElementById(`${type}-bank-fields`);
        const arrow = document.getElementById(`${type}-bank-arrow`);

        if (!section) return;

        if (section.style.display === "none" || section.style.display === "") {
            section.style.display = "block";
            if (arrow) arrow.style.transform = "rotate(180deg)";
        } else {
            section.style.display = "none";
            if (arrow) arrow.style.transform = "rotate(0deg)";
        }
    }

    // Gestion des checkboxes
    function toggleAllCheckboxes(masterCheckbox) {

        const checkboxes = document.querySelectorAll('.client-checkbox');

        let selectedType = null;
        let hasConflict = false;

        checkboxes.forEach(cb => {

            if (!selectedType) {
                selectedType = cb.dataset.type;
            } else if (selectedType !== cb.dataset.type) {
                hasConflict = true;
            }
        });

        if (hasConflict) {

            masterCheckbox.checked = false;

            showErrorModal("Impossible de mélanger professionnels et particuliers");

            return;
        }

        // ✅ sinon ok
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });

        updateSelectionBar();
    }

    function updateSelectionBar(event) {
        const checkboxes = document.querySelectorAll('.client-checkbox');
        const checked = Array.from(checkboxes).filter(cb => cb.checked);

        const selectedCount = checked.length;
        const selectionBar = document.getElementById('selectionBar');
        const exportBtn = document.getElementById('exportBtn');

        let selectedType = null;
        let hasConflict = false;

        // 🔥 Détecter type unique
        checked.forEach(cb => {
            if (!selectedType) {
                selectedType = cb.dataset.type;
            } else if (selectedType !== cb.dataset.type) {
                hasConflict = true;
            }
        });

        if (hasConflict) {

                if (event && event.target) {
                    event.target.checked = false;
                }

                const exportBtn = document.getElementById('exportBtn');
                if (exportBtn) {
                    exportBtn.disabled = true;
                }

                showErrorModal("Impossible de mélanger professionnels et particuliers");

                return;
            }

        // UI sélection
        if (selectedCount > 0) {
            selectionBar.style.display = 'flex';
            document.getElementById('selectedCount').textContent = selectedCount;
        } else {
            selectionBar.style.display = 'none';
        }

        // 🔥 Bouton dynamique
        if (exportBtn) {
            if (selectedCount === 0) {
                exportBtn.disabled = true;
                exportBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Exporter';
            } else if (selectedType === 'particulier') {
                exportBtn.disabled = false;
                exportBtn.classList.remove('btn-sync');
                exportBtn.classList.add('btn-success');
                exportBtn.innerHTML = '<i class="fas fa-user"></i> Exporter particuliers';
            } else {
                exportBtn.disabled = false;
                exportBtn.classList.remove('btn-success');
                exportBtn.classList.add('btn-sync');
                exportBtn.innerHTML = '<i class="fas fa-building"></i> Exporter professionnels';
            }
        }

        // 🔥 BONUS : bloquer les autres types
        checkboxes.forEach(cb => {
            if (selectedType && cb.dataset.type !== selectedType && !cb.checked) {
                cb.disabled = true;
            } else {
                cb.disabled = false;
            }
        });
    }

    function showErrorModal(message) {
        document.getElementById('errorMessage').innerText = message;
        openModal('errorModal');
    }

    function selectAll() {
        const checkboxes = document.querySelectorAll('.client-checkbox');
        checkboxes.forEach(cb => cb.checked = true);
        updateSelectionBar();
    }

    function deselectAll() {
        const checkboxes = document.querySelectorAll('.client-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        updateSelectionBar();
    }


    /*function syncTiime() {
        showToast('Synchronisation avec Tiime en cours...', 'info');
        
        const syncBtn = document.querySelector('.btn-sync');
        if (syncBtn) {
            const originalText = syncBtn.innerHTML;
            syncBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Synchronisation...';
            syncBtn.disabled = true;
            
            // Simuler un délai de synchronisation
            setTimeout(() => {
                showToast('Synchronisation avec Tiime terminée avec succès !', 'success');
                syncBtn.innerHTML = originalText;
                syncBtn.disabled = false;
            }, 2000);
        }
    }*/

    // Toast notification
    function showToast(message, type = 'info') {
        // Supprimer les toasts existants
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        
        toast.innerHTML = `
            <i class="fas fa-${icon} toast-icon"></i>
            <span class="toast-message">${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => {
                if (toast.parentNode) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    function openEditModal(clientId) {

        fetch("/invoice/clients/" + clientId + "/edit")
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors du chargement du client");
            }
            return response.json();
        })
        .then(data => {

            // ID
            document.getElementById('editClientId').value = data.id ?? '';

            // TYPE CLIENT
            if (data.type === "professionnel") {
                document.getElementById('editTypePro').checked = true;
            } else {
                document.getElementById('editTypePart').checked = true;
            }

            // ENTREPRISE
            document.getElementById('editCompanyName').value = data.company_name ?? '';
            document.getElementById('editSiret').value = data.siret ?? '';
            document.getElementById('editTva').value = data.tva ?? '';

            // PARTICULIER
            document.getElementById('editFirstName').value = data.first_name ?? '';
            document.getElementById('editLastName').value = data.last_name ?? '';

            // EMAIL / PHONE (pour les deux types)
            document.getElementById('editEmail').value = data.email ?? '';
            document.getElementById('editEmailParticulier').value = data.email ?? '';

        const phoneData = splitPhone(data.phone);

    // SET PREFIX
    const prefixSelects = document.querySelectorAll('#editClientModal .phonePrefix');
    prefixSelects.forEach(select => select.value = phoneData.prefix);

    // SET COUNTRY (IMPORTANT)
    const country = getCountryFromPrefix(phoneData.prefix);
    document.getElementById('editCountry').value = country;

    // SET PHONE
    document.getElementById('editPhone').value = phoneData.number;
    document.getElementById('editPhoneParticulier').value = phoneData.number;
            // ADRESSE
            document.getElementById('editAddress').value = data.address ?? '';
            document.getElementById('editAddressComplement').value = data.address_complement ?? '';
            document.getElementById('editPostalCode').value = data.postal_code ?? '';
            document.getElementById('editCity').value = data.city ?? '';

            // BANQUE
            document.getElementById('editIban').value = data.iban ?? '';
            document.getElementById('editBic').value = data.bic ?? '';

            // CHECKBOX
            document.getElementById('editIncludeAddress').checked = data.include_address == 1;

            // AFFICHER BON TYPE
            toggleClientTypeFields('edit');

            // ouvrir modal
            openModal('editClientModal');

        })
        .catch(error => {
            console.error(error);
            alert("Impossible de charger les données du client.");
        });
    }

    document.querySelectorAll('.checkbox-column').forEach(col => {
        col.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    function splitPhone(phone) {

        if (!phone) return { prefix: '+33', number: '' };

        const prefixes = ['+33', '+32', '+41', '+352'];

        for (let p of prefixes) {
            if (phone.startsWith(p)) {
                return {
                    prefix: p,
                    number: phone.replace(p, '')
                };
            }
        }
    

        return { prefix: '+33', number: phone };
    }

    function getCountryFromPrefix(prefix) {
        const map = {
            '+33': 'France',
            '+32': 'Belgique',
            '+41': 'Suisse',
            '+352': 'Luxembourg'
        };
        return map[prefix] ?? 'France';
    }
    // Sauvegarde des modifications
    function saveClientChanges() {
        const form = document.getElementById('editClientForm');
        const clientId = document.getElementById('editClientId').value;

        let url = "{{ route('clients.update', ':id') }}";
        url = url.replace(':id', clientId);

        form.action = url;
        form.submit();
    }

    // Confirmation de suppression
    function confirmDelete(event, message) {
        if (confirm(message)) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }

    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser l'affichage des champs pour le modal d'ajout
        toggleClientTypeFields('add');
        
        // Initialiser la section bancaire repliée
        const addBankFields = document.getElementById('add-bank-fields');
        if (addBankFields) addBankFields.style.display = 'none';
        
        const editBankFields = document.getElementById('edit-bank-fields');
        if (editBankFields) editBankFields.style.display = 'none';
        
        // Fermeture des modales en cliquant sur l'overlay
        const overlays = document.querySelectorAll('.modal-overlay');
        overlays.forEach(overlay => {
            overlay.addEventListener('click', function() {
                const modal = this.closest('.modal');
                if (modal) {
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });

        // Fermeture des modales avec la touche Echap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    modal.classList.remove('show');
                });
                document.body.style.overflow = '';
            }
        });
        
        // Initialiser la barre de sélection
        updateSelectionBar();
    });

    // Ajouter le style pour slideOutRight
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    function openViewClientModal(clientId) {

    currentEditClientId = clientId;

    fetch("/invoice/clients/" + clientId + "/edit")
    .then(response => {
        if (!response.ok) {
            throw new Error("Erreur lors du chargement du client");
        }
        return response.json();
    })

    .then(data => {

        /* TYPE CLIENT */

        const typeText = data.type === "professionnel" ? "Professionnel" : "Particulier";
        document.getElementById("viewType").innerText = typeText;

        const proFields = document.getElementById("view-pro-fields");
        const partFields = document.getElementById("view-part-fields");

        if (data.type === "professionnel") {

            if (proFields) proFields.style.display = "block";
            if (partFields) partFields.style.display = "none";

            document.getElementById("viewCompany").innerText = data.company_name ?? "";
            document.getElementById("viewSiret").innerText = data.siret ?? "-";
            document.getElementById("viewTva").innerText = data.tva ?? "-";

        } else {

            if (proFields) proFields.style.display = "none";
            if (partFields) partFields.style.display = "block";

            document.getElementById("viewFirstName").innerText = data.first_name ?? "";
            document.getElementById("viewLastName").innerText = data.last_name ?? "";

        }

        /* EMAIL / PHONE */

        document.getElementById("viewEmail").innerText = data.email ?? "";
        document.getElementById("viewPhone").innerText = data.phone ?? "";

        /* ADRESSE */

        document.getElementById("viewAddress").innerText = data.address ?? "";
        document.getElementById("viewAddressComplement").innerText = data.address_complement ?? "";
        document.getElementById("viewPostal").innerText = data.postal_code ?? "";
        document.getElementById("viewCity").innerText = data.city ?? "";
        document.getElementById("viewCountry").innerText = data.country ?? "";

        /* BANQUE */

        document.getElementById("viewIban").innerText = data.iban ?? "-";
        document.getElementById("viewBic").innerText = data.bic ?? "-";

        /* OUVRIR MODAL */

        openModal("viewClientModal");

    })

    .catch(error => {

        console.error(error);
        alert("Impossible de charger les informations du client.");

    });

    }

    function updatePhonePrefix(country) {

        const prefixes = {
            'France': '+33',
            'Belgique': '+32',
            'Suisse': '+41',
            'Luxembourg': '+352'
        };

        const prefixInputs = document.querySelectorAll('.phonePrefix');

        prefixInputs.forEach(input => {
            if (prefixes[country]) {
                input.value = prefixes[country];
            }
        });
    }

    document.getElementById('editClientForm').addEventListener('submit', function() {

        const prefix = document.querySelector('#editClientModal .phonePrefix')?.value ?? '';
        
        const phoneInput = document.getElementById('editPhone') 
            || document.getElementById('editPhoneParticulier');

        if (phoneInput && phoneInput.value && !phoneInput.value.startsWith('+')) {
            phoneInput.value = prefix + phoneInput.value;
        }
    });

    function submitExport() {

        const checkboxes = document.querySelectorAll('.client-checkbox:checked');

        let selectedType = null;
        let hasConflict = false;
        let ids = [];

        for (let cb of checkboxes) {

            // 🔴 BLOQUER SI DEJA EXPORTE
            if (cb.dataset.exported == "1") {
                showErrorModal("Ce client est déjà exporté ❌");
                return;
            }

            ids.push(cb.value);

            if (!selectedType) {
                selectedType = cb.dataset.type;
            } else if (selectedType !== cb.dataset.type) {
                hasConflict = true;
            }
        }

        if (hasConflict) {
            showErrorModal("Impossible de mélanger professionnels et particuliers");
            return;
        }

        document.getElementById('client_ids').value = ids.join(',');
        document.getElementById('exportForm').submit();
    }

    function handleCheckboxClick(event) {

        const checkbox = event.target;
        const isExported = checkbox.dataset.exported == "1";

        if (isExported) {
            event.preventDefault();
            event.stopPropagation();

            showErrorModal("Ce client est déjà exporté ❌");
            return;
        }

        // ✅ seulement ici on met à jour
        updateSelectionBar(event);

        event.stopPropagation();
    }

    </script>
    @endsection