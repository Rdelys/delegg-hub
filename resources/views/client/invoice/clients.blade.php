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
            <button class="btn btn-export" type="button" onclick="exportToTiime()" id="exportTiimeBtn" disabled>
                <i class="fas fa-file-export"></i> Exporter vers Tiime
            </button>
            <button class="btn btn-sync" type="button" onclick="syncTiime()">
                <i class="fas fa-sync-alt"></i> Synchronisation Tiime
            </button>
        </div>
    </div>

    {{-- BARRE D'ACTIONS DE SÉLECTION --}}
    <div class="selection-bar" id="selectionBar" style="display: none;">
        <div class="selection-info">
            <span id="selectedCount">0</span> client(s) sélectionné(s)
        </div>
        <div class="selection-actions">
            <button class="btn btn-outline" onclick="selectAll()">
                <i class="fas fa-check-double"></i> Tout sélectionner
            </button>
            <button class="btn btn-outline" onclick="deselectAll()">
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
                        <th class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Données statiques pour les clients --}}
                    <tr data-client-id="1">
                        <td class="checkbox-column">
                            <label class="checkbox-label">
                                <input type="checkbox" class="client-checkbox" value="1" onchange="updateSelectionBar()">
                                <span class="checkbox-custom"></span>
                            </label>
                        </td>
                        <td>
                            <div class="company-cell">
                                <span class="company-name">Tech Solutions SARL</span>
                                <small class="company-siret">SIRET: 823 456 789 00012</small>
                            </div>
                        </td>
                        <td>contact@techsolutions.fr</td>
                        <td>01 42 68 94 32</td>
                        <td>Lyon</td>
                        <td><span class="badge badge-pro">Professionnel</span></td>
                        <td class="actions-cell">
                            <button class="btn-icon btn-edit" onclick="openEditModal(1)" title="Modifier" type="button">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="deleteClient(1)" title="Supprimer" type="button">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-client-id="2">
                        <td class="checkbox-column">
                            <label class="checkbox-label">
                                <input type="checkbox" class="client-checkbox" value="2" onchange="updateSelectionBar()">
                                <span class="checkbox-custom"></span>
                            </label>
                        </td>
                        <td>
                            <div class="company-cell">
                                <span class="company-name">Innovation Plus</span>
                                <small class="company-siret">SIRET: 912 345 678 00045</small>
                            </div>
                        </td>
                        <td>contact@innovation-plus.fr</td>
                        <td>04 91 23 45 67</td>
                        <td>Marseille</td>
                        <td><span class="badge badge-pro">Professionnel</span></td>
                        <td class="actions-cell">
                            <button class="btn-icon btn-edit" onclick="openEditModal(2)" title="Modifier" type="button">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="deleteClient(2)" title="Supprimer" type="button">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-client-id="3">
                        <td class="checkbox-column">
                            <label class="checkbox-label">
                                <input type="checkbox" class="client-checkbox" value="3" onchange="updateSelectionBar()">
                                <span class="checkbox-custom"></span>
                            </label>
                        </td>
                        <td>
                            <div class="company-cell">
                                <span class="company-name">Digital Factory</span>
                                <small class="company-siret">SIRET: 734 567 891 00023</small>
                            </div>
                        </td>
                        <td>contact@digitalfactory.fr</td>
                        <td>05 57 89 12 34</td>
                        <td>Bordeaux</td>
                        <td><span class="badge badge-pro">Professionnel</span></td>
                        <td class="actions-cell">
                            <button class="btn-icon btn-edit" onclick="openEditModal(3)" title="Modifier" type="button">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="deleteClient(3)" title="Supprimer" type="button">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr data-client-id="4">
                        <td class="checkbox-column">
                            <label class="checkbox-label">
                                <input type="checkbox" class="client-checkbox" value="4" onchange="updateSelectionBar()">
                                <span class="checkbox-custom"></span>
                            </label>
                        </td>
                        <td>
                            <div class="company-cell">
                                <span class="company-name">Jean Dupont</span>
                                <small class="company-siret">Particulier</small>
                            </div>
                        </td>
                        <td>jean.dupont@email.com</td>
                        <td>06 12 34 56 78</td>
                        <td>Paris</td>
                        <td><span class="badge badge-part">Particulier</span></td>
                        <td class="actions-cell">
                            <button class="btn-icon btn-edit" onclick="openEditModal(4)" title="Modifier" type="button">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="deleteClient(4)" title="Supprimer" type="button">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
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
                        <button class="nav-link active" onclick="switchTab('add', 'info', event)">Informations</button>
                        <button class="nav-link" onclick="switchTab('add', 'contact', event)">Contacts</button>
                        <button class="nav-link" onclick="switchTab('add', 'notes', event)">Notes</button>
                    </div>

                    <form id="addClientForm">
                        {{-- TAB INFORMATIONS --}}
                        <div class="tab-pane active" id="add-info-tab">
                            <div class="form-section">
                                <h6 class="section-title">Type de client</h6>
                                <div class="client-type-group">
                                    <label class="radio-label">
                                        <input type="radio" name="addClientType" value="professionnel" checked onchange="toggleClientTypeFields('add')">
                                        <span class="radio-custom"></span>
                                        Professionnel
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="addClientType" value="particulier" onchange="toggleClientTypeFields('add')">
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
                                        <input type="text" class="form-control" id="addCompanyName" placeholder="Nom de l'entreprise *" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addSiret" placeholder="N° de SIREN ou SIRET">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addTva" placeholder="N° de TVA intracom">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="addEmail" placeholder="Mail *" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="addPhone" placeholder="Téléphone">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Champs pour Particulier --}}
                            <div id="add-particulier-fields" style="display: none;">
                                <div class="form-section">
                                    <h6 class="section-title">Informations personnelles</h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="addLastName" placeholder="Nom *" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="addFirstName" placeholder="Prénom *" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="addEmailParticulier" placeholder="Mail *" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="addPhoneParticulier" placeholder="Téléphone">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Toggle d'inclusion d'adresse --}}
                            <div class="form-section">
                                <div class="toggle-container">
                                    <label class="toggle-label">
                                        <input type="checkbox" class="toggle-checkbox" id="addIncludeAddress" checked>
                                        <span class="toggle-switch"></span>
                                        <span class="toggle-text">Inclure cette adresse lors des envois par mail</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-section">
                                <h6 class="section-title">Adresse</h6>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="addAddress" placeholder="Adresse *" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="addAddressComplement" placeholder="Complément d'adresse">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="addPostalCode" placeholder="Code postal *" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="addCity" placeholder="Ville *" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="addCountry" placeholder="Pays *" required value="France">
                                </div>
                            </div>

                            <div class="form-section collapsible">
                                <div class="collapsible-header" onclick="toggleBankSection('add')">
                                    <span>Informations bancaires</span>
                                    <span class="arrow" id="add-bank-arrow">▼</span>
                                </div>
                                <div class="collapsible-body" id="add-bank-fields" style="display: none;">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addIban" placeholder="IBAN">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addBic" placeholder="BIC">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB CONTACTS MULTIPLES --}}
                        <div class="tab-pane" id="add-contact-tab">
                            <div class="form-section">
                                <div class="section-header">
                                    <h6 class="section-title">Contacts</h6>
                                    <button type="button" class="btn-add-contact" onclick="addContactField('add')">
                                        <i class="fas fa-plus"></i> Ajouter un contact
                                    </button>
                                </div>
                                <div id="add-contacts-container">
                                    {{-- Premier contact par défaut --}}
                                    <div class="contact-card" id="add-contact-0">
                                        <div class="contact-header">
                                            <span class="contact-number">Contact 1</span>
                                            <button type="button" class="btn-remove-contact" onclick="removeContactField('add', 0)" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="addContactLastName[]" placeholder="Nom">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="addContactFirstName[]" placeholder="Prénom">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="addContactFunction[]" placeholder="Fonction">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="addContactEmail[]" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <input type="tel" class="form-control" name="addContactPhone[]" placeholder="Téléphone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB NOTES --}}
                        <div class="tab-pane" id="add-notes-tab">
                            <div class="form-section">
                                <h6 class="section-title">Notes client</h6>
                                <textarea class="form-control" id="addNotes" rows="6" placeholder="Informations complémentaires, remarques, historique des échanges..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addClientModal')">
                        Annuler
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveClient('add')">
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
                        <button class="nav-link active" onclick="switchTab('edit', 'info', event)">Informations</button>
                        <button class="nav-link" onclick="switchTab('edit', 'contact', event)">Contacts</button>
                        <button class="nav-link" onclick="switchTab('edit', 'notes', event)">Notes</button>
                    </div>

                    <form id="editClientForm">
                        <input type="hidden" id="editClientId">
                        
                        {{-- TAB INFORMATIONS --}}
                        <div class="tab-pane active" id="edit-info-tab">
                            <div class="form-section">
                                <h6 class="section-title">Type de client</h6>
                                <div class="client-type-group">
                                    <label class="radio-label">
                                        <input type="radio" name="editClientType" value="professionnel" id="editTypePro" onchange="toggleClientTypeFields('edit')">
                                        <span class="radio-custom"></span>
                                        Professionnel
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="editClientType" value="particulier" id="editTypePart" onchange="toggleClientTypeFields('edit')">
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
                                        <input type="text" class="form-control" id="editCompanyName" placeholder="Nom de l'entreprise *">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="editSiret" placeholder="N° de SIREN ou SIRET">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="editTva" placeholder="N° de TVA intracom">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="editEmail" placeholder="Mail *">
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="editPhone" placeholder="Téléphone">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Champs pour Particulier --}}
                            <div id="edit-particulier-fields" style="display: none;">
                                <div class="form-section">
                                    <h6 class="section-title">Informations personnelles</h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editLastName" placeholder="Nom *">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editFirstName" placeholder="Prénom *">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="editEmailParticulier" placeholder="Mail *">
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="editPhoneParticulier" placeholder="Téléphone">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Toggle d'inclusion d'adresse --}}
                            <div class="form-section">
                                <div class="toggle-container">
                                    <label class="toggle-label">
                                        <input type="checkbox" class="toggle-checkbox" id="editIncludeAddress">
                                        <span class="toggle-switch"></span>
                                        <span class="toggle-text">Inclure cette adresse lors des envois par mail</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-section">
                                <h6 class="section-title">Adresse</h6>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="editAddress" placeholder="Adresse *">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="editAddressComplement" placeholder="Complément d'adresse">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editPostalCode" placeholder="Code postal *">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editCity" placeholder="Ville *">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="editCountry" placeholder="Pays *">
                                </div>
                            </div>

                            <div class="form-section collapsible">
                                <div class="collapsible-header" onclick="toggleBankSection('edit')">
                                    <span>Informations bancaires</span>
                                    <span class="arrow" id="edit-bank-arrow">▼</span>
                                </div>
                                <div class="collapsible-body" id="edit-bank-fields" style="display: none;">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="editIban" placeholder="IBAN">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="editBic" placeholder="BIC">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB CONTACTS MULTIPLES --}}
                        <div class="tab-pane" id="edit-contact-tab">
                            <div class="form-section">
                                <div class="section-header">
                                    <h6 class="section-title">Contacts</h6>
                                    <button type="button" class="btn-add-contact" onclick="addContactField('edit')">
                                        <i class="fas fa-plus"></i> Ajouter un contact
                                    </button>
                                </div>
                                <div id="edit-contacts-container">
                                    {{-- Les contacts seront chargés dynamiquement --}}
                                </div>
                            </div>
                        </div>

                        {{-- TAB NOTES --}}
                        <div class="tab-pane" id="edit-notes-tab">
                            <div class="form-section">
                                <h6 class="section-title">Notes client</h6>
                                <textarea class="form-control" id="editNotes" rows="6" placeholder="Informations complémentaires..."></textarea>
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

<script>
// Données statiques des clients
const clientsData = {
    1: {
        type: 'professionnel',
        companyName: 'Tech Solutions SARL',
        siret: '82345678900012',
        tva: 'FR23823456789',
        email: 'contact@techsolutions.fr',
        phone: '0142689432',
        address: '15 Rue de la République',
        addressComplement: 'Bâtiment B',
        postalCode: '69002',
        city: 'Lyon',
        country: 'France',
        iban: 'FR76 1234 5678 9012 3456 7890 123',
        bic: 'CMCIFR2A',
        includeAddress: true,
        contacts: [
            {
                lastName: 'Martin',
                firstName: 'Sophie',
                function: 'Directrice Commerciale',
                email: 's.martin@techsolutions.fr',
                phone: '0645789123'
            },
            {
                lastName: 'Bernard',
                firstName: 'Pierre',
                function: 'Responsable Technique',
                email: 'p.bernard@techsolutions.fr',
                phone: '0678901234'
            }
        ],
        notes: 'Client fidèle depuis 2019. Projets majeurs : refonte site web, application mobile.'
    },
    2: {
        type: 'professionnel',
        companyName: 'Innovation Plus',
        siret: '91234567800045',
        tva: 'FR91912345678',
        email: 'contact@innovation-plus.fr',
        phone: '0491234567',
        address: '45 Rue de la Canebière',
        addressComplement: '',
        postalCode: '13001',
        city: 'Marseille',
        country: 'France',
        iban: 'FR76 9876 5432 1098 7654 3210 987',
        bic: 'AGRIFRPP',
        includeAddress: false,
        contacts: [
            {
                lastName: 'Dubois',
                firstName: 'Thomas',
                function: 'Responsable Projet',
                email: 't.dubois@innovation-plus.fr',
                phone: '0623344556'
            }
        ],
        notes: 'Nouveau client depuis janvier 2024. Première mission en cours.'
    },
    3: {
        type: 'professionnel',
        companyName: 'Digital Factory',
        siret: '73456789100023',
        tva: 'FR34734567891',
        email: 'contact@digitalfactory.fr',
        phone: '0557891234',
        address: '8 Cours de l\'Intendance',
        addressComplement: '3ème étage',
        postalCode: '33000',
        city: 'Bordeaux',
        country: 'France',
        iban: 'FR76 5678 1234 9012 3456 7890 234',
        bic: 'BNPAFRPP',
        includeAddress: true,
        contacts: [
            {
                lastName: 'Bernard',
                firstName: 'Julie',
                function: 'Directrice Technique',
                email: 'j.bernard@digitalfactory.fr',
                phone: '0655678899'
            }
        ],
        notes: 'Client très exigeant mais fidèle. Plusieurs projets réussis.'
    },
    4: {
        type: 'particulier',
        lastName: 'Dupont',
        firstName: 'Jean',
        email: 'jean.dupont@email.com',
        phone: '0612345678',
        address: '12 Rue de Rivoli',
        addressComplement: '',
        postalCode: '75004',
        city: 'Paris',
        country: 'France',
        iban: '',
        bic: '',
        includeAddress: true,
        contacts: [
            {
                lastName: 'Dupont',
                firstName: 'Jean',
                function: '',
                email: 'jean.dupont@email.com',
                phone: '0612345678'
            }
        ],
        notes: 'Client particulier. Demande de devis pour site vitrine.'
    }
};

// Variable pour stocker l'ID du client en cours d'édition
let currentEditClientId = null;
let contactCounters = { add: 1, edit: 0 };

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
    document.querySelector('input[name="addClientType"][value="professionnel"]').checked = true;
    document.getElementById('addIncludeAddress').checked = true;
    document.getElementById('addCountry').value = 'France';
    
    // Réinitialiser les contacts
    const contactsContainer = document.getElementById('add-contacts-container');
    contactsContainer.innerHTML = `
        <div class="contact-card" id="add-contact-0">
            <div class="contact-header">
                <span class="contact-number">Contact 1</span>
                <button type="button" class="btn-remove-contact" onclick="removeContactField('add', 0)" style="display: none;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="text" class="form-control" name="addContactLastName[]" placeholder="Nom">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="addContactFirstName[]" placeholder="Prénom">
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="addContactFunction[]" placeholder="Fonction">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="email" class="form-control" name="addContactEmail[]" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="tel" class="form-control" name="addContactPhone[]" placeholder="Téléphone">
                </div>
            </div>
        </div>
    `;
    contactCounters.add = 1;
}

// Gestion des onglets
function switchTab(type, tabName, event) {
    event.preventDefault();
    
    // Désactiver tous les onglets
    const tabs = document.querySelectorAll(`#${type}ClientModal .nav-link`);
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Activer l'onglet cliqué
    event.target.classList.add('active');
    
    // Cacher tous les contenus d'onglets
    const panes = document.querySelectorAll(`#${type}ClientModal .tab-pane`);
    panes.forEach(pane => pane.classList.remove('active'));
    
    // Afficher le contenu correspondant
    const targetPane = document.getElementById(`${type}-${tabName}-tab`);
    if (targetPane) {
        targetPane.classList.add('active');
    }
}

// Gestion des contacts multiples
function addContactField(modalType) {
    const container = document.getElementById(`${modalType}-contacts-container`);
    const contactCount = container.children.length;
    const newIndex = contactCount;
    
    const contactCard = document.createElement('div');
    contactCard.className = 'contact-card';
    contactCard.id = `${modalType}-contact-${newIndex}`;
    
    contactCard.innerHTML = `
        <div class="contact-header">
            <span class="contact-number">Contact ${contactCount + 1}</span>
            <button type="button" class="btn-remove-contact" onclick="removeContactField('${modalType}', ${newIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="form-row">
            <div class="form-group">
                <input type="text" class="form-control" name="${modalType}ContactLastName[]" placeholder="Nom">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="${modalType}ContactFirstName[]" placeholder="Prénom">
            </div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="${modalType}ContactFunction[]" placeholder="Fonction">
        </div>
        <div class="form-row">
            <div class="form-group">
                <input type="email" class="form-control" name="${modalType}ContactEmail[]" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" name="${modalType}ContactPhone[]" placeholder="Téléphone">
            </div>
        </div>
    `;
    
    container.appendChild(contactCard);
    
    // Mettre à jour les numéros de contact
    updateContactNumbers(modalType);
}

function removeContactField(modalType, index) {
    const contactCard = document.getElementById(`${modalType}-contact-${index}`);
    if (contactCard) {
        contactCard.remove();
        updateContactNumbers(modalType);
    }
}

function updateContactNumbers(modalType) {
    const container = document.getElementById(`${modalType}-contacts-container`);
    const contacts = container.children;
    
    for (let i = 0; i < contacts.length; i++) {
        const contact = contacts[i];
        const numberSpan = contact.querySelector('.contact-number');
        if (numberSpan) {
            numberSpan.textContent = `Contact ${i + 1}`;
        }
        
        // Mettre à jour l'ID et l'attribut onclick du bouton de suppression
        const removeBtn = contact.querySelector('.btn-remove-contact');
        if (removeBtn) {
            const oldId = contact.id.split('-').pop();
            contact.id = `${modalType}-contact-${i}`;
            removeBtn.setAttribute('onclick', `removeContactField('${modalType}', ${i})`);
        }
    }
}

function getContactsFromForm(modalType) {
    const container = document.getElementById(`${modalType}-contacts-container`);
    const contacts = [];
    
    for (let i = 0; i < container.children.length; i++) {
        const contact = container.children[i];
        const lastName = contact.querySelector(`input[name="${modalType}ContactLastName[]"]`)?.value || '';
        const firstName = contact.querySelector(`input[name="${modalType}ContactFirstName[]"]`)?.value || '';
        const func = contact.querySelector(`input[name="${modalType}ContactFunction[]"]`)?.value || '';
        const email = contact.querySelector(`input[name="${modalType}ContactEmail[]"]`)?.value || '';
        const phone = contact.querySelector(`input[name="${modalType}ContactPhone[]"]`)?.value || '';
        
        contacts.push({
            lastName,
            firstName,
            function: func,
            email,
            phone
        });
    }
    
    return contacts;
}

function loadContactsIntoForm(modalType, contacts) {
    const container = document.getElementById(`${modalType}-contacts-container`);
    container.innerHTML = '';
    
    if (contacts && contacts.length > 0) {
        contacts.forEach((contact, index) => {
            const contactCard = document.createElement('div');
            contactCard.className = 'contact-card';
            contactCard.id = `${modalType}-contact-${index}`;
            
            contactCard.innerHTML = `
                <div class="contact-header">
                    <span class="contact-number">Contact ${index + 1}</span>
                    <button type="button" class="btn-remove-contact" onclick="removeContactField('${modalType}', ${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" class="form-control" name="${modalType}ContactLastName[]" value="${escapeHtml(contact.lastName || '')}" placeholder="Nom">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="${modalType}ContactFirstName[]" value="${escapeHtml(contact.firstName || '')}" placeholder="Prénom">
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="${modalType}ContactFunction[]" value="${escapeHtml(contact.function || '')}" placeholder="Fonction">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="email" class="form-control" name="${modalType}ContactEmail[]" value="${escapeHtml(contact.email || '')}" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="tel" class="form-control" name="${modalType}ContactPhone[]" value="${escapeHtml(contact.phone || '')}" placeholder="Téléphone">
                    </div>
                </div>
            `;
            
            container.appendChild(contactCard);
        });
    } else {
        // Ajouter un contact vide par défaut
        addContactField(modalType);
    }
}

// Helper pour échapper les caractères HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Basculement des champs selon le type de client
function toggleClientTypeFields(modalType) {
    const radioName = modalType === 'add' ? 'addClientType' : 'editClientType';
    const selected = document.querySelector(`input[name="${radioName}"]:checked`);

    if (!selected) return;

    const isProfessional = selected.value === 'professionnel';

    const proFields = document.getElementById(`${modalType}-professionnel-fields`);
    const partFields = document.getElementById(`${modalType}-particulier-fields`);

    if (isProfessional) {
        proFields.style.display = 'block';
        partFields.style.display = 'none';
    } else {
        proFields.style.display = 'none';
        partFields.style.display = 'block';
    }
}

// Gestion des sections repliables
function toggleBankSection(type) {
    const section = document.getElementById(`${type}-bank-fields`);
    const arrow = document.getElementById(`${type}-bank-arrow`);

    if (!section) return;

    if (section.style.display === "none" || section.style.display === "") {
        section.style.display = "block";
        arrow.style.transform = "rotate(180deg)";
    } else {
        section.style.display = "none";
        arrow.style.transform = "rotate(0deg)";
    }
}

// Gestion des checkboxes
function toggleAllCheckboxes(checkbox) {
    const checkboxes = document.querySelectorAll('.client-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSelectionBar();
}

function updateSelectionBar() {
    const checkboxes = document.querySelectorAll('.client-checkbox');
    const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const exportBtn = document.getElementById('exportTiimeBtn');
    const selectionBar = document.getElementById('selectionBar');
    
    // Mettre à jour le checkbox "Tout sélectionner"
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = selectedCount === checkboxes.length && checkboxes.length > 0;
        selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < checkboxes.length;
    }
    
    // Afficher/masquer la barre de sélection
    if (selectedCount > 0) {
        selectionBar.style.display = 'flex';
        document.getElementById('selectedCount').textContent = selectedCount;
        exportBtn.disabled = false;
    } else {
        selectionBar.style.display = 'none';
        exportBtn.disabled = true;
    }
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

// Fonctions Tiime
function exportToTiime() {
    const checkboxes = document.querySelectorAll('.client-checkbox:checked');
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        showToast('Veuillez sélectionner au moins un client à exporter', 'error');
        return;
    }
    
    // Simuler l'export
    showToast(`Export de ${selectedIds.length} client(s) vers Tiime en cours...`, 'info');
    
    // Désactiver temporairement le bouton
    const exportBtn = document.getElementById('exportTiimeBtn');
    exportBtn.disabled = true;
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Export...';
    
    // Simuler un délai d'export
    setTimeout(() => {
        showToast(`${selectedIds.length} client(s) exporté(s) avec succès vers Tiime !`, 'success');
        exportBtn.disabled = false;
        exportBtn.innerHTML = '<i class="fas fa-file-export"></i> Exporter vers Tiime';
        
        // Désélectionner tous les clients après export
        deselectAll();
    }, 2000);
}

function syncTiime() {
    showToast('Synchronisation avec Tiime en cours...', 'info');
    
    const syncBtn = document.querySelector('.btn-sync');
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

// Toast notification
function showToast(message, type = 'info') {
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
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Ouverture du modal d'édition
function openEditModal(clientId) {
    console.log('Opening modal for client:', clientId);
    
    const client = clientsData[clientId];
    if (!client) {
        console.error('Client not found:', clientId);
        return;
    }

    currentEditClientId = clientId;
    document.getElementById('editClientId').value = clientId;

    // Type de client
    if (client.type === 'professionnel') {
        document.getElementById('editTypePro').checked = true;
        toggleClientTypeFields('edit');
        
        // Remplir les champs professionnel
        document.getElementById('editCompanyName').value = client.companyName || '';
        document.getElementById('editSiret').value = client.siret || '';
        document.getElementById('editTva').value = client.tva || '';
        document.getElementById('editEmail').value = client.email || '';
        document.getElementById('editPhone').value = client.phone || '';
        
        // Vider les champs particulier
        document.getElementById('editLastName').value = '';
        document.getElementById('editFirstName').value = '';
        document.getElementById('editEmailParticulier').value = '';
        document.getElementById('editPhoneParticulier').value = '';
    } else {
        document.getElementById('editTypePart').checked = true;
        toggleClientTypeFields('edit');
        
        // Remplir les champs particulier
        document.getElementById('editLastName').value = client.lastName || '';
        document.getElementById('editFirstName').value = client.firstName || '';
        document.getElementById('editEmailParticulier').value = client.email || '';
        document.getElementById('editPhoneParticulier').value = client.phone || '';
        
        // Vider les champs professionnel
        document.getElementById('editCompanyName').value = '';
        document.getElementById('editSiret').value = '';
        document.getElementById('editTva').value = '';
        document.getElementById('editEmail').value = '';
        document.getElementById('editPhone').value = '';
    }
    
    // Adresse
    document.getElementById('editAddress').value = client.address || '';
    document.getElementById('editAddressComplement').value = client.addressComplement || '';
    document.getElementById('editPostalCode').value = client.postalCode || '';
    document.getElementById('editCity').value = client.city || '';
    document.getElementById('editCountry').value = client.country || 'France';
    
    // Coordonnées bancaires
    document.getElementById('editIban').value = client.iban || '';
    document.getElementById('editBic').value = client.bic || '';
    
    // Toggle d'inclusion d'adresse
    document.getElementById('editIncludeAddress').checked = client.includeAddress || false;
    
    // Contacts multiples
    loadContactsIntoForm('edit', client.contacts || []);
    
    // Notes
    document.getElementById('editNotes').value = client.notes || '';
    
    // Ouvrir le modal
    openModal('editClientModal');
}

// Sauvegarde des modifications
function saveClientChanges() {
    const clientId = document.getElementById('editClientId').value;
    const clientType = document.querySelector('input[name="editClientType"]:checked').value;
    
    // Récupérer les données du formulaire
    const clientData = {
        type: clientType,
        includeAddress: document.getElementById('editIncludeAddress').checked,
        address: document.getElementById('editAddress').value,
        addressComplement: document.getElementById('editAddressComplement').value,
        postalCode: document.getElementById('editPostalCode').value,
        city: document.getElementById('editCity').value,
        country: document.getElementById('editCountry').value,
        iban: document.getElementById('editIban').value,
        bic: document.getElementById('editBic').value,
        contacts: getContactsFromForm('edit'),
        notes: document.getElementById('editNotes').value
    };
    
    if (clientType === 'professionnel') {
        clientData.companyName = document.getElementById('editCompanyName').value;
        clientData.siret = document.getElementById('editSiret').value;
        clientData.tva = document.getElementById('editTva').value;
        clientData.email = document.getElementById('editEmail').value;
        clientData.phone = document.getElementById('editPhone').value;
    } else {
        clientData.lastName = document.getElementById('editLastName').value;
        clientData.firstName = document.getElementById('editFirstName').value;
        clientData.email = document.getElementById('editEmailParticulier').value;
        clientData.phone = document.getElementById('editPhoneParticulier').value;
    }
    
    // Mettre à jour les données (simulation)
    clientsData[clientId] = { ...clientsData[clientId], ...clientData };
    
    showToast('Client modifié avec succès !', 'success');
    closeModal('editClientModal');
    
    // Recharger la page pour voir les modifications (simulation)
    setTimeout(() => {
        location.reload();
    }, 1500);
}

// Sauvegarde d'un nouveau client
function saveClient(type) {
    const clientType = document.querySelector('input[name="addClientType"]:checked').value;
    
    // Validation de base
    if (clientType === 'professionnel') {
        if (!document.getElementById('addCompanyName').value || !document.getElementById('addEmail').value) {
            showToast('Veuillez remplir tous les champs obligatoires', 'error');
            return;
        }
    } else {
        if (!document.getElementById('addLastName').value || !document.getElementById('addFirstName').value || !document.getElementById('addEmailParticulier').value) {
            showToast('Veuillez remplir tous les champs obligatoires', 'error');
            return;
        }
    }
    
    if (!document.getElementById('addAddress').value || !document.getElementById('addPostalCode').value || !document.getElementById('addCity').value) {
        showToast('Veuillez remplir l\'adresse complète', 'error');
        return;
    }
    
    // Récupérer les contacts
    const contacts = getContactsFromForm('add');
    
    showToast('Client ajouté avec succès !', 'success');
    closeModal('addClientModal');
    
    // Simuler l'ajout du client
    setTimeout(() => {
        location.reload();
    }, 1500);
}

// Suppression d'un client
function deleteClient(clientId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
        showToast('Client supprimé avec succès !', 'success');
        
        // Simuler la suppression
        setTimeout(() => {
            location.reload();
        }, 1500);
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser l'affichage des champs pour le modal d'ajout
    toggleClientTypeFields('add');
    
    // Initialiser la section bancaire repliée
    document.getElementById('add-bank-fields').style.display = 'none';
    document.getElementById('edit-bank-fields').style.display = 'none';
    
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

// Animation de slideOut pour les toasts
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
</script>
@endsection