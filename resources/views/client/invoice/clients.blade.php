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
            <button class="btn btn-export" type="button">
                <i class="fas fa-file-export"></i> Export vers Tiime
            </button>
        </div>
    </div>

    {{-- TABLEAU DES CLIENTS --}}
    <div class="card">
        <div class="table-responsive">
            <table class="clients-table">
                <thead>
                    <tr>
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
                    <tr>
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
                    <tr>
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
                    <tr>
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
                    <tr>
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
                        <button class="nav-link" onclick="switchTab('add', 'contact', event)">Contact</button>
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
                                    <h6 class="section-title">Informations</h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="addName" placeholder="Nom *" required>
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

                            {{-- Toggle d'inclusion d'adresse --}}
                            <div class="form-section">
                                <div class="toggle-container">
                                    <label class="toggle-label">
                                        <input type="checkbox" class="toggle-checkbox" id="addIncludeAddress">
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
                                    <span>Informations complémentaires</span>
                                    <span class="arrow" id="add-bank-arrow">▲</span>
                                </div>

                                <div class="collapsible-body" id="add-bank-fields">

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

                        {{-- TAB CONTACT --}}
                        <div class="tab-pane" id="add-contact-tab">
                            <div class="form-section">
                                <h6 class="section-title">Contact principal</h6>
                                <div class="contact-card">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addContactLastName" placeholder="Nom">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addContactFirstName" placeholder="Prénom">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="addContactFunction" placeholder="Fonction">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="addContactEmail" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="addContactPhone" placeholder="Téléphone">
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

                            <div class="form-section">
                                <h6 class="section-title">Tags</h6>
                                <div class="tags-container" id="addTags">
                                    <span class="tag" onclick="toggleTag(this)">Important</span>
                                    <span class="tag" onclick="toggleTag(this)">Fidèle</span>
                                    <span class="tag" onclick="toggleTag(this)">Nouveau</span>
                                    <span class="tag" onclick="toggleTag(this)">À relancer</span>
                                </div>
                                <input type="text" class="form-control mt-2" placeholder="Ajouter un tag...">
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
                        <button class="nav-link" onclick="switchTab('edit', 'contact', event)">Contact</button>
                        <button class="nav-link" onclick="switchTab('edit', 'notes', event)">Notes</button>
                    </div>

                    <form id="editClientForm">
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
                                        <input type="text" class="form-control" id="editCompanyName" placeholder="Nom de l'entreprise *" required>
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
                                            <input type="email" class="form-control" id="editEmail" placeholder="Mail *" required>
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
                                    <h6 class="section-title">Informations</h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editName" placeholder="Nom *" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="editEmail" placeholder="Mail *" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="editPhone" placeholder="Téléphone">
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
                                    <input type="text" class="form-control" id="editAddress" placeholder="Adresse *" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="editAddressComplement" placeholder="Complément d'adresse">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editPostalCode" placeholder="Code postal *" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editCity" placeholder="Ville *" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="editCountry" placeholder="Pays *" required>
                                </div>
                            </div>

                            <div class="form-section">
                                <h6 class="section-title">Coordonnées bancaires</h6>
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

                        {{-- TAB CONTACT --}}
                        <div class="tab-pane" id="edit-contact-tab">
                            <div class="form-section">
                                <h6 class="section-title">Contact principal</h6>
                                <div class="contact-card">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="editContactLastName" placeholder="Nom">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="editContactFirstName" placeholder="Prénom">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="editContactFunction" placeholder="Fonction">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="editContactEmail" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="editContactPhone" placeholder="Téléphone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB NOTES --}}
                        <div class="tab-pane" id="edit-notes-tab">
                            <div class="form-section">
                                <h6 class="section-title">Notes client</h6>
                                <textarea class="form-control" id="editNotes" rows="6" placeholder="Informations complémentaires..."></textarea>
                            </div>

                            <div class="form-section">
                                <h6 class="section-title">Tags</h6>
                                <div class="tags-container" id="editTags"></div>
                                <input type="text" class="form-control mt-2" placeholder="Ajouter un tag...">
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
}

.collapsible-body {
    padding: 16px;
    border-top: 1px solid var(--border-light);
}

.arrow {
    transition: transform 0.2s ease;
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

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 8px -1px rgba(34, 197, 94, 0.3);
}

.btn-export {
    background: var(--bg-white);
    color: var(--text-light);
    border: 1px solid var(--border-light);
}

.btn-export:hover {
    background: var(--bg-light);
    border-color: var(--text-lighter);
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
}

.clients-table {
    width: 100%;
    border-collapse: collapse;
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
}

/* TABS */
.nav-tabs {
    display: flex;
    gap: 4px;
    border-bottom: 2px solid var(--border-light);
    margin-bottom: 24px;
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
}

/* TAGS */
.tags-container {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.tag {
    padding: 4px 12px;
    background: var(--border-light);
    border-radius: 20px;
    font-size: 0.85rem;
    color: var(--text-medium);
    cursor: pointer;
    transition: all 0.2s ease;
}

.tag:hover {
    background: var(--text-lighter);
}

.tag.selected {
    background: var(--primary);
    color: white;
}

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
        gap: 16px;
        align-items: flex-start;
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
        contact: {
            lastName: 'Martin',
            firstName: 'Sophie',
            function: 'Directrice Commerciale',
            email: 's.martin@techsolutions.fr',
            phone: '0645789123'
        },
        notes: 'Client fidèle depuis 2019. Projets majeurs : refonte site web, application mobile.',
        tags: ['Important', 'Fidèle']
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
        contact: {
            lastName: 'Dubois',
            firstName: 'Thomas',
            function: 'Responsable Projet',
            email: 't.dubois@innovation-plus.fr',
            phone: '0623344556'
        },
        notes: 'Nouveau client depuis janvier 2024. Première mission en cours.',
        tags: ['Nouveau']
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
        contact: {
            lastName: 'Bernard',
            firstName: 'Julie',
            function: 'Directrice Technique',
            email: 'j.bernard@digitalfactory.fr',
            phone: '0655678899'
        },
        notes: 'Client très exigeant mais fidèle. Plusieurs projets réussis.',
        tags: ['Important']
    },
    4: {
        type: 'particulier',
        name: 'Jean Dupont',
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
        contact: {
            lastName: 'Dupont',
            firstName: 'Jean',
            function: '',
            email: 'jean.dupont@email.com',
            phone: '0612345678'
        },
        notes: 'Client particulier. Demande de devis pour site vitrine.',
        tags: ['À relancer']
    }
};

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
    }
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

// Gestion des tags
function toggleTag(element) {
    element.classList.toggle('selected');
}

// Basculement des champs selon le type de client
function toggleClientTypeFields(modalType) {

    const radioName = modalType === 'add' ? 'addClientType' : 'editClientType';

    const selected = document.querySelector(`input[name="${radioName}"]:checked`);

    if (!selected) return;

    const isProfessional = selected.value === 'professionnel';

    const proFields = document.getElementById(`${modalType}-professionnel-fields`);
    const partFields = document.getElementById(`${modalType}-particulier-fields`);

    const siretField = document.getElementById(`${modalType}Siret`);
    const tvaField = document.getElementById(`${modalType}Tva`);

    if (isProfessional) {

        proFields.style.display = 'block';
        partFields.style.display = 'none';

    } else {

        proFields.style.display = 'none';
        partFields.style.display = 'block';

        // vider SIRET et TVA
        if (siretField) siretField.value = '';
        if (tvaField) tvaField.value = '';
    }
}

// Ouverture du modal d'édition
function openEditModal(clientId) {
    console.log('Opening modal for client:', clientId);
    
    const client = clientsData[clientId];
    if (!client) {
        console.error('Client not found:', clientId);
        return;
    }

    // Type de client
    if (client.type === 'professionnel') {
        document.getElementById('editTypePro').checked = true;
        document.getElementById('edit-professionnel-fields').style.display = 'block';
        document.getElementById('edit-particulier-fields').style.display = 'none';
        
        // Remplir les champs professionnel
        document.getElementById('editCompanyName').value = client.companyName || '';
        document.getElementById('editSiret').value = client.siret || '';
        document.getElementById('editTva').value = client.tva || '';
        document.getElementById('editEmail').value = client.email || '';
        document.getElementById('editPhone').value = client.phone || '';
    } else {
        document.getElementById('editTypePart').checked = true;
        document.getElementById('edit-professionnel-fields').style.display = 'none';
        document.getElementById('edit-particulier-fields').style.display = 'block';
        
        // Remplir les champs particulier
        document.getElementById('editName').value = client.name || '';
        document.getElementById('editEmail').value = client.email || '';
        document.getElementById('editPhone').value = client.phone || '';
        
        // Vider les champs SIRET et TVA
        document.getElementById('editSiret').value = '';
        document.getElementById('editTva').value = '';
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
    
    // Contact
    if (client.contact) {
        document.getElementById('editContactLastName').value = client.contact.lastName || '';
        document.getElementById('editContactFirstName').value = client.contact.firstName || '';
        document.getElementById('editContactFunction').value = client.contact.function || '';
        document.getElementById('editContactEmail').value = client.contact.email || '';
        document.getElementById('editContactPhone').value = client.contact.phone || '';
    }
    
    // Notes
    document.getElementById('editNotes').value = client.notes || '';
    
    // Tags
    const tagsContainer = document.getElementById('editTags');
    tagsContainer.innerHTML = '';
    if (client.tags && client.tags.length > 0) {
        client.tags.forEach(tag => {
            const tagSpan = document.createElement('span');
            tagSpan.className = 'tag selected';
            tagSpan.textContent = tag;
            tagSpan.onclick = function() { toggleTag(this); };
            tagsContainer.appendChild(tagSpan);
        });
    }
    
    // Ouvrir le modal
    openModal('editClientModal');
}

// Sauvegarde des modifications
function saveClientChanges() {
    alert('Modifications enregistrées avec succès !');
    closeModal('editClientModal');
}

// Sauvegarde d'un nouveau client
function saveClient(type) {
    alert('Client ajouté avec succès !');
    closeModal('addClientModal');
}

// Suppression d'un client
function deleteClient(clientId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
        alert('Client supprimé avec succès !');
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser l'affichage des champs pour le modal d'ajout
    const addProRadio = document.querySelector('input[name="clientType"][value="professionnel"]');
    if (addProRadio) {
        addProRadio.checked = true;
        toggleClientTypeFields('add');
    }
    
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
});

function toggleBankSection(type) {

    const section = document.getElementById(`${type}-bank-fields`);
    const arrow = document.getElementById(`${type}-bank-arrow`);

    if (!section) return;

    if (section.style.display === "none") {
        section.style.display = "block";
        arrow.style.transform = "rotate(0deg)";
    } else {
        section.style.display = "none";
        arrow.style.transform = "rotate(180deg)";
    }
}
</script>
@endsection