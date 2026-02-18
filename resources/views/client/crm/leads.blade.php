@extends('client.layouts.app')

@section('title', 'Mes Leads')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="header-left">
            <h1>Mes Leads</h1>
            <p>Liste des prospects importés depuis Google & Web.</p>
        </div>
        <div class="header-actions">
            <button class="btn-excel" onclick="exportAllExcel()">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </button>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i>
                Ajouter un lead
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">156</span>
                <span class="stat-label">Total leads</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">23</span>
                <span class="stat-label">À relancer</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">12</span>
                <span class="stat-label">RDV pris</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">8</span>
                <span class="stat-label">Clôturés</span>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="filters-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un lead...">
        </div>
        <div class="filter-actions">
            <select class="filter-select">
                <option>Tous les statuts</option>
                <option>En cours</option>
                <option>À relancer</option>
                <option>RDV proposé</option>
                <option>RDV pris</option>
                <option>Refus</option>
                <option>Clôturé</option>
            </select>
            <select class="filter-select">
                <option>Toute chaleur</option>
                <option>Froid</option>
                <option>Tiède</option>
                <option>Chaud</option>
            </select>
            <button class="btn-filter">
                <i class="fas fa-sliders-h"></i>
                Filtres
            </button>
        </div>
    </div>

    <!-- Table responsive -->
    <div class="table-responsive">
        <table class="leads-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="select-all" onclick="event.stopPropagation()">
                    </th>
                    <th>Prénom Nom</th>
                    <th>Commentaire</th>
                    <th>Chaleur</th>
                    <th>Status</th>
                    <th>Status Relance</th>
                    <th>Enfants %</th>
                    <th>Date statut</th>
                    <th>LinkedIn</th>
                    <th>Suivi Mail</th>
                    <th>WhatsApp</th>
                    <th>Téléphone</th>
                    <th>MP Insta</th>
                    <th>Follow Insta</th>
                    <th>Com Insta</th>
                    <th>Formulaire</th>
                    <th>Messenger</th>
                    <th>Entreprise</th>
                    <th>Fonction</th>
                    <th>Email</th>
                    <th>Tel Fixe</th>
                    <th>Portable</th>
                    <th>URL LinkedIn</th>
                    <th>URL Maps</th>
                    <th>Site web</th>
                    <th>Compte Insta</th>
                    <th>Devis</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Ligne de test 1 -->
                <tr onclick="openEditModal(1)" style="cursor: pointer;">
                    <td onclick="event.stopPropagation()"><input type="checkbox" class="select-row"></td>
                    <td>
                        <div class="lead-info">
                            <strong>Jean Dupont</strong>
                            <small>ID: LD-2025-001</small>
                        </div>
                    </td>
                    <td>Client potentiel, intéressé par nos services</td>
                    <td><span class="badge chaud">Chaud</span></td>
                    <td><span class="badge encours">En cours</span></td>
                    <td><span class="badge relance-status">J+2 – Email relance</span></td>
                    <td><span class="badge pourcent">60%</span></td>
                    <td>15/02/2025</td>
                    <td><span class="badge attente">En attente</span></td>
                    <td><span class="badge">Mail 2</span></td>
                    <td><span class="badge">WhatsApp 1</span></td>
                    <td><span class="badge">Message 2</span></td>
                    <td><span class="badge">MP 1</span></td>
                    <td class="checkbox-cell" onclick="event.stopPropagation()"><input type="checkbox" checked disabled></td>
                    <td><span class="badge">Com 1</span></td>
                    <td><span class="badge attente">Oui, attente</span></td>
                    <td><span class="badge succes">Oui, reçu</span></td>
                    <td>TechCorp SAS</td>
                    <td>CTO</td>
                    <td>jean.d@techcorp.com</td>
                    <td>0145879632</td>
                    <td>0612345678</td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">linkedin.com/in/jeandupont</a>
                    </td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">maps.app.goo.gl/abc123</a>
                    </td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">techcorp.com</a>
                    </td>
                    <td>@techcorp_officiel</td>
                    <td><span class="badge envoye">Envoyé</span></td>
                    <td class="actions" onclick="event.stopPropagation()">
                        <button class="btn-icon" onclick="openEditModal(1)" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon" onclick="openDeleteModal(1, 'Jean Dupont')" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn-icon" onclick="exportLead(1)" title="Exporter">
                            <i class="fas fa-file-excel"></i>
                        </button>
                    </td>
                </tr>
                <!-- Ligne de test 2 -->
                <tr onclick="openEditModal(2)" style="cursor: pointer;">
                    <td onclick="event.stopPropagation()"><input type="checkbox" class="select-row"></td>
                    <td>
                        <div class="lead-info">
                            <strong>Marie Martin</strong>
                            <small>ID: LD-2025-002</small>
                        </div>
                    </td>
                    <td>A demandé un devis, à relancer</td>
                    <td><span class="badge tiede">Tiède</span></td>
                    <td><span class="badge relance">À relancer</span></td>
                    <td><span class="badge relance-status">J+1 – Relance réseaux</span></td>
                    <td><span class="badge pourcent">40%</span></td>
                    <td>10/02/2025</td>
                    <td><span class="badge valide">Validé</span></td>
                    <td><span class="badge">Mail 1</span></td>
                    <td><span class="badge">PAS DE PORTABLE</span></td>
                    <td><span class="badge">Message 1</span></td>
                    <td><span class="badge">MP 2</span></td>
                    <td class="checkbox-cell" onclick="event.stopPropagation()"><input type="checkbox" disabled></td>
                    <td><span class="badge">Com 2</span></td>
                    <td><span class="badge succes">Oui, reçu</span></td>
                    <td><span class="badge">Non, Pas de compte</span></td>
                    <td>Design Studio</td>
                    <td>Directrice artistique</td>
                    <td>m.martin@designstudio.fr</td>
                    <td>-</td>
                    <td>0623456789</td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">linkedin.com/in/mariemartin</a>
                    </td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">maps.app.goo.gl/def456</a>
                    </td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">designstudio.fr</a>
                    </td>
                    <td>@design_studio_off</td>
                    <td><span class="badge">A faire</span></td>
                    <td class="actions" onclick="event.stopPropagation()">
                        <button class="btn-icon" onclick="openEditModal(2)" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon" onclick="openDeleteModal(2, 'Marie Martin')" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn-icon" onclick="exportLead(2)" title="Exporter">
                            <i class="fas fa-file-excel"></i>
                        </button>
                    </td>
                </tr>
                <!-- Ligne de test 3 -->
                <tr onclick="openEditModal(3)" style="cursor: pointer;">
                    <td onclick="event.stopPropagation()"><input type="checkbox" class="select-row"></td>
                    <td>
                        <div class="lead-info">
                            <strong>Pierre Dubois</strong>
                            <small>ID: LD-2025-003</small>
                        </div>
                    </td>
                    <td>RDV confirmé pour la semaine prochaine</td>
                    <td><span class="badge chaud">Chaud</span></td>
                    <td><span class="badge rdv-pris">RDV pris</span></td>
                    <td><span class="badge rdv-badge">RDV pris</span></td>
                    <td><span class="badge pourcent">80%</span></td>
                    <td>18/02/2025</td>
                    <td><span class="badge valide">Validé</span></td>
                    <td><span class="badge">Mail 4</span></td>
                    <td><span class="badge">WhatsApp 3</span></td>
                    <td><span class="badge">Message 3</span></td>
                    <td><span class="badge">MP 3</span></td>
                    <td class="checkbox-cell" onclick="event.stopPropagation()"><input type="checkbox" checked disabled></td>
                    <td><span class="badge">Com 3</span></td>
                    <td><span class="badge succes">Oui, reçu</span></td>
                    <td><span class="badge succes">Oui, reçu</span></td>
                    <td>Innovation SARL</td>
                    <td>CEO</td>
                    <td>pierre.d@innovation.fr</td>
                    <td>0156789345</td>
                    <td>0645678901</td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">linkedin.com/in/pierredubois</a>
                    </td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">maps.app.goo.gl/ghi789</a>
                    </td>
                    <td class="url-cell">
                        <a href="#" class="url-link" onclick="event.stopPropagation()">innovation.fr</a>
                    </td>
                    <td>@innovation_off</td>
                    <td><span class="badge envoye">Validé</span></td>
                    <td class="actions" onclick="event.stopPropagation()">
                        <button class="btn-icon" onclick="openEditModal(3)" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon" onclick="openDeleteModal(3, 'Pierre Dubois')" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn-icon" onclick="exportLead(3)" title="Exporter">
                            <i class="fas fa-file-excel"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info">
            Affichage de 1 à 3 sur 156 leads
        </div>
        <div class="pagination-controls">
            <button class="btn-pagination" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="btn-pagination active">1</button>
            <button class="btn-pagination">2</button>
            <button class="btn-pagination">3</button>
            <span>...</span>
            <button class="btn-pagination">13</button>
            <button class="btn-pagination">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal Ajouter/Modifier -->
<div id="leadModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Ajouter un lead</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="leadForm" onsubmit="saveLead(event)">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Prénom Nom du Lead *</label>
                        <input type="text" id="prenomNom" required>
                    </div>
                    <div class="form-group">
                        <label>Commentaire</label>
                        <textarea id="commentaire" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Chaleur du lead</label>
                        <select id="chaleur">
                            <option>Froid</option>
                            <option>Tiède</option>
                            <option>Chaud</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status du lead</label>
                        <select id="status">
                            <option>En cours</option>
                            <option>À relancer plus tard</option>
                            <option>Répondu – à traiter</option>
                            <option>RDV proposé</option>
                            <option>RDV pris</option>
                            <option>Refus</option>
                            <option>Clôturé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status Relance</label>
                        <select id="statusRelance">
                            <option>J0 – Email envoyé</option>
                            <option>J0 – Réseaux envoyé</option>
                            <option>J+1 – Relance réseaux</option>
                            <option>J+2 – Email relance</option>
                            <option>J+3 – WhatsApp/SMS 1</option>
                            <option>J+4 – Email angle problème</option>
                            <option>J+5 – Réseaux angle problème</option>
                            <option>J+7 – Email proposition RDV</option>
                            <option>J+8 – WhatsApp/SMS 2</option>
                            <option>J+10 – Email final</option>
                            <option>J+12 – Réseaux final</option>
                            <option>Répondu – à traiter</option>
                            <option>RDV proposé</option>
                            <option>RDV pris</option>
                            <option>Refus</option>
                            <option>À relancer plus tard</option>
                            <option>Clôturé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lead à jour de ses enfants</label>
                        <select id="enfants">
                            <option>0%</option>
                            <option>40%</option>
                            <option>60%</option>
                            <option>80%</option>
                            <option>100%</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date du statut</label>
                        <input type="date" id="dateStatut">
                    </div>
                    <div class="form-group">
                        <label>Contacter sur LinkedIn</label>
                        <select id="linkedin">
                            <option>Validé</option>
                            <option>Non</option>
                            <option>En attente de réponse</option>
                            <option>Refusé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Suivi Mail</label>
                        <select id="mail">
                            <option>Mail 1</option>
                            <option>Mail 2</option>
                            <option>Mail 3</option>
                            <option>Mail 4</option>
                            <option>Mail 5</option>
                            <option>Mail arrive en SPAM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Suivi WhatsApp</label>
                        <select id="whatsapp">
                            <option>WhatsApp 1</option>
                            <option>WhatsApp 2</option>
                            <option>WhatsApp 3</option>
                            <option>WhatsApp 4</option>
                            <option>WhatsApp 5</option>
                            <option>PAS DE PORTABLE</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Appel téléphonique</label>
                        <select id="appel">
                            <option>Message 1</option>
                            <option>Message 2</option>
                            <option>Message 3</option>
                            <option>Message 4</option>
                            <option>Message 5</option>
                            <option>Mort</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>MP instagram</label>
                        <select id="mp">
                            <option>MP 1</option>
                            <option>MP 2</option>
                            <option>MP 3</option>
                            <option>MP 4</option>
                            <option>MP 5</option>
                            <option>Mort</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Follow Insta</label>
                        <input type="checkbox" id="followInsta">
                    </div>
                    <div class="form-group">
                        <label>Com Insta</label>
                        <select id="com">
                            <option>Com 1</option>
                            <option>Com 2</option>
                            <option>Com 3</option>
                            <option>Com 4</option>
                            <option>Com 5</option>
                            <option>Mort</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Formulaire site</label>
                        <select id="formulaire">
                            <option>Oui, attente réponse</option>
                            <option>Oui, avec réponse reçu</option>
                            <option>Non, Pas de Formulaire</option>
                            <option>Mort</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message Messenger</label>
                        <select id="messenger">
                            <option>Oui, attente réponse</option>
                            <option>Oui, avec réponse reçu</option>
                            <option>Non, Pas de compte</option>
                            <option>Mort</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nom entreprise</label>
                        <input type="text" id="entreprise">
                    </div>
                    <div class="form-group">
                        <label>Fonction du prospect</label>
                        <input type="text" id="fonction">
                    </div>
                    <div class="form-group">
                        <label>Email Scrappé</label>
                        <input type="email" id="email">
                    </div>
                    <div class="form-group">
                        <label>Tel Fixe</label>
                        <input type="text" id="telFixe">
                    </div>
                    <div class="form-group">
                        <label>Portable du Lead</label>
                        <input type="text" id="portable">
                    </div>
                    <div class="form-group">
                        <label>URL LinkedIn</label>
                        <input type="url" id="urlLinkedin">
                    </div>
                    <div class="form-group">
                        <label>URL Google Maps</label>
                        <input type="url" id="urlMaps">
                    </div>
                    <div class="form-group">
                        <label>URL Site internet</label>
                        <input type="url" id="urlSite">
                    </div>
                    <div class="form-group">
                        <label>Compte Insta</label>
                        <input type="text" id="compteInsta">
                    </div>
                    <div class="form-group">
                        <label>Devis</label>
                        <select id="devis">
                            <option>Pas encore</option>
                            <option>A faire</option>
                            <option>Envoyé</option>
                            <option>Validé</option>
                            <option>Perdu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Suppression -->
<div id="deleteModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Confirmer la suppression</h2>
            <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer le lead : <strong id="deleteLeadName"></strong> ?</p>
            <p style="color: #ef4444; margin-top: 10px;">Cette action est irréversible.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Annuler</button>
            <button type="button" class="btn-danger" onclick="confirmDelete()">Supprimer</button>
        </div>
    </div>
</div>

<style>
    /* Import Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    * {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f0f4f8;
    }

    /* Premium Card Style */
    .card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px -12px rgba(0, 20, 30, 0.15), 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 28px;
        margin: 20px;
        border: 1px solid rgba(226, 232, 240, 0.6);
        backdrop-filter: blur(8px);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 30px 50px -12px rgba(0, 20, 30, 0.2);
    }

    /* Header Styles */
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 28px;
    }

    .header-left h1 {
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(135deg, #1a2639, #2d3b55);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0 0 4px 0;
        letter-spacing: -0.5px;
    }

    .header-left p {
        color: #5f6b7a;
        font-size: 15px;
        margin: 0;
        font-weight: 400;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* Button Styles */
    .btn-excel {
        background: linear-gradient(135deg, #0e6b3e, #1e8a4f);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(14, 107, 62, 0.2);
    }

    .btn-excel:hover {
        background: linear-gradient(135deg, #0a5330, #15733f);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(14, 107, 62, 0.3);
    }

    .btn-add {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: linear-gradient(135deg, #f8fafc, #f1f4f9);
        border-radius: 20px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid rgba(203, 213, 225, 0.4);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: #94a3b8;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; }
    .stat-icon.green { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534; }
    .stat-icon.yellow { background: linear-gradient(135deg, #fed7aa, #fde68a); color: #92400e; }
    .stat-icon.purple { background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #6b21a8; }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 13px;
        color: #5f6b7a;
        font-weight: 500;
    }

    /* Filters Bar */
    .filters-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 10px 16px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        min-width: 280px;
    }

    .search-box i {
        color: #94a3b8;
        font-size: 14px;
    }

    .search-box input {
        border: none;
        outline: none;
        font-size: 14px;
        width: 100%;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 10px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        font-size: 14px;
        color: #1e293b;
        cursor: pointer;
        outline: none;
    }

    .btn-filter {
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #1e293b;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: #f1f5f9;
    }

    /* Premium Table */
    .table-responsive {
        overflow-x: auto;
        margin: 0 -28px;
        padding: 0 28px;
        border-radius: 20px;
    }

    .leads-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 2300px;
        font-size: 13px;
    }

    .leads-table th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 16px 12px;
        text-align: left;
        font-weight: 600;
        color: #1e293b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #d1d5db;
        white-space: nowrap;
    }

    .leads-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        white-space: nowrap;
        vertical-align: middle;
    }

    .leads-table tbody tr {
        transition: all 0.2s;
        cursor: pointer;
    }

    .leads-table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    /* Lead Info */
    .lead-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .lead-info strong {
        color: #0f172a;
        font-weight: 600;
    }

    .lead-info small {
        color: #64748b;
        font-size: 11px;
    }

    /* Checkbox */
    .select-all, .select-row {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 2px solid #cbd5e1;
        cursor: pointer;
    }

    .checkbox-cell {
        text-align: center;
    }

    /* URL Links */
    .url-cell {
        max-width: 150px;
    }

    .url-link {
        color: #2563eb;
        text-decoration: none;
        font-size: 12px;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .url-link:hover {
        text-decoration: underline;
        color: #1d4ed8;
    }

    /* Badges améliorés */
    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .badge.chaud { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fecaca; }
    .badge.tiede { background: linear-gradient(135deg, #fff3cd, #ffe69c); color: #856404; border: 1px solid #ffe69c; }
    .badge.froid { background: linear-gradient(135deg, #e2e3e5, #d3d6d8); color: #383d41; border: 1px solid #d3d6d8; }
    .badge.encours { background: linear-gradient(135deg, #cfe2ff, #b6d4fe); color: #052c65; border: 1px solid #b6d4fe; }
    .badge.relance { background: linear-gradient(135deg, #fff3cd, #ffe69c); color: #856404; border: 1px solid #ffe69c; }
    .badge.relance-status { background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #6b21a8; border: 1px solid #d8b4fe; }
    .badge.rdv-badge { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.pourcent { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.attente { background: linear-gradient(135deg, #cff4fc, #b6effb); color: #055160; border: 1px solid #b6effb; }
    .badge.valide { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.succes { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.envoye { background: linear-gradient(135deg, #cfe2ff, #b6d4fe); color: #052c65; border: 1px solid #b6d4fe; }
    .badge.rdv-pris { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.refus { background: linear-gradient(135deg, #f8d7da, #f5c2c7); color: #842029; border: 1px solid #f5c2c7; }

    /* Actions */
    .actions {
        display: flex;
        gap: 6px;
    }

    .btn-icon {
        background: white;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        padding: 8px;
        border-radius: 10px;
        color: #5f6b7a;
        transition: all 0.2s;
        font-size: 14px;
    }

    .btn-icon:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #94a3b8;
        transform: translateY(-1px);
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        color: #5f6b7a;
        font-size: 14px;
    }

    .pagination-controls {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-pagination {
        min-width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #334155;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-pagination:hover:not(:disabled) {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .btn-pagination.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .btn-pagination:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Modal styles améliorés */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        overflow-y: auto;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        border-radius: 28px;
        max-width: 900px;
        margin: 20px auto;
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .modal-header {
        padding: 24px 28px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 28px 28px 0 0;
    }

    .modal-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .close-btn {
        background: white;
        border: 1px solid #e2e8f0;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        font-size: 20px;
        cursor: pointer;
        color: #5f6b7a;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .close-btn:hover {
        background: #f1f5f9;
        color: #ef4444;
        border-color: #fecaca;
    }

    .modal-body {
        padding: 28px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 14px;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .form-group input:hover,
    .form-group select:hover,
    .form-group textarea:hover {
        border-color: #94a3b8;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .modal-footer {
        padding: 24px 28px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        background: #f8fafc;
        border-radius: 0 0 28px 28px;
    }

    .btn-primary,
    .btn-secondary,
    .btn-danger {
        padding: 12px 24px;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #334155;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .card {
            padding: 20px;
            margin: 12px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            flex-direction: column;
        }

        .btn-excel, .btn-add {
            width: 100%;
            justify-content: center;
        }

        .filters-bar {
            flex-direction: column;
        }

        .search-box {
            width: 100%;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-select {
            flex: 1;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            padding: 14px;
        }

        .pagination {
            flex-direction: column;
            align-items: center;
        }

        .modal-content {
            margin: 10px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .card {
            padding: 16px;
            margin: 8px;
            border-radius: 18px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            width: 100%;
        }

        .pagination-controls {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

<script>
    // Variables globales pour les modales
    let currentLeadId = null;
    let deleteLeadId = null;

    // Fonctions d'export Excel (statiques pour le moment)
    function exportAllExcel() {
        alert('Export Excel de tous les leads (fonction statique)');
    }

    function exportLead(leadId) {
        alert(`Export Excel du lead ID: ${leadId} (fonction statique)`);
    }

    // Ouvrir modal d'ajout
    function openAddModal() {
        currentLeadId = null;
        document.getElementById('modalTitle').textContent = 'Ajouter un lead';
        document.getElementById('leadForm').reset();
        document.getElementById('leadModal').style.display = 'block';
    }

    // Ouvrir modal d'édition
    function openEditModal(leadId) {
        currentLeadId = leadId;
        document.getElementById('modalTitle').textContent = 'Modifier le lead';
        
        // Simulation de chargement des données
        if (leadId === 1) {
            document.getElementById('prenomNom').value = 'Jean Dupont';
            document.getElementById('commentaire').value = 'Client potentiel, intéressé par nos services';
            document.getElementById('chaleur').value = 'Chaud';
            document.getElementById('status').value = 'En cours';
            document.getElementById('statusRelance').value = 'J+2 – Email relance';
            document.getElementById('enfants').value = '60%';
            document.getElementById('dateStatut').value = '2025-02-15';
            document.getElementById('linkedin').value = 'En attente de réponse';
            document.getElementById('mail').value = 'Mail 2';
            document.getElementById('whatsapp').value = 'WhatsApp 1';
            document.getElementById('appel').value = 'Message 2';
            document.getElementById('mp').value = 'MP 1';
            document.getElementById('followInsta').checked = true;
            document.getElementById('com').value = 'Com 1';
            document.getElementById('formulaire').value = 'Oui, attente réponse';
            document.getElementById('messenger').value = 'Oui, avec réponse reçu';
            document.getElementById('entreprise').value = 'TechCorp SAS';
            document.getElementById('fonction').value = 'CTO';
            document.getElementById('email').value = 'jean.d@techcorp.com';
            document.getElementById('telFixe').value = '0145879632';
            document.getElementById('portable').value = '0612345678';
            document.getElementById('urlLinkedin').value = 'linkedin.com/in/jeandupont';
            document.getElementById('urlMaps').value = 'maps.app.goo.gl/abc123';
            document.getElementById('urlSite').value = 'techcorp.com';
            document.getElementById('compteInsta').value = '@techcorp_officiel';
            document.getElementById('devis').value = 'Envoyé';
        } else if (leadId === 2) {
            document.getElementById('prenomNom').value = 'Marie Martin';
            document.getElementById('commentaire').value = 'A demandé un devis, à relancer';
            document.getElementById('chaleur').value = 'Tiède';
            document.getElementById('status').value = 'À relancer plus tard';
            document.getElementById('statusRelance').value = 'J+1 – Relance réseaux';
            document.getElementById('enfants').value = '40%';
            document.getElementById('dateStatut').value = '2025-02-10';
            document.getElementById('linkedin').value = 'Validé';
            document.getElementById('mail').value = 'Mail 1';
            document.getElementById('whatsapp').value = 'PAS DE PORTABLE';
            document.getElementById('appel').value = 'Message 1';
            document.getElementById('mp').value = 'MP 2';
            document.getElementById('followInsta').checked = false;
            document.getElementById('com').value = 'Com 2';
            document.getElementById('formulaire').value = 'Oui, avec réponse reçu';
            document.getElementById('messenger').value = 'Non, Pas de compte';
            document.getElementById('entreprise').value = 'Design Studio';
            document.getElementById('fonction').value = 'Directrice artistique';
            document.getElementById('email').value = 'm.martin@designstudio.fr';
            document.getElementById('telFixe').value = '-';
            document.getElementById('portable').value = '0623456789';
            document.getElementById('urlLinkedin').value = 'linkedin.com/in/mariemartin';
            document.getElementById('urlMaps').value = 'maps.app.goo.gl/def456';
            document.getElementById('urlSite').value = 'designstudio.fr';
            document.getElementById('compteInsta').value = '@design_studio_off';
            document.getElementById('devis').value = 'A faire';
        } else {
            document.getElementById('prenomNom').value = 'Pierre Dubois';
            document.getElementById('commentaire').value = 'RDV confirmé pour la semaine prochaine';
            document.getElementById('chaleur').value = 'Chaud';
            document.getElementById('status').value = 'RDV pris';
            document.getElementById('statusRelance').value = 'RDV pris';
            document.getElementById('enfants').value = '80%';
            document.getElementById('dateStatut').value = '2025-02-18';
            document.getElementById('linkedin').value = 'Validé';
            document.getElementById('mail').value = 'Mail 4';
            document.getElementById('whatsapp').value = 'WhatsApp 3';
            document.getElementById('appel').value = 'Message 3';
            document.getElementById('mp').value = 'MP 3';
            document.getElementById('followInsta').checked = true;
            document.getElementById('com').value = 'Com 3';
            document.getElementById('formulaire').value = 'Oui, avec réponse reçu';
            document.getElementById('messenger').value = 'Oui, avec réponse reçu';
            document.getElementById('entreprise').value = 'Innovation SARL';
            document.getElementById('fonction').value = 'CEO';
            document.getElementById('email').value = 'pierre.d@innovation.fr';
            document.getElementById('telFixe').value = '0156789345';
            document.getElementById('portable').value = '0645678901';
            document.getElementById('urlLinkedin').value = 'linkedin.com/in/pierredubois';
            document.getElementById('urlMaps').value = 'maps.app.goo.gl/ghi789';
            document.getElementById('urlSite').value = 'innovation.fr';
            document.getElementById('compteInsta').value = '@innovation_off';
            document.getElementById('devis').value = 'Validé';
        }
        
        document.getElementById('leadModal').style.display = 'block';
    }

    // Fermer modal
    function closeModal() {
        document.getElementById('leadModal').style.display = 'none';
    }

    // Sauvegarder lead
    function saveLead(event) {
        event.preventDefault();
        alert(currentLeadId ? 'Lead modifié avec succès !' : 'Lead ajouté avec succès !');
        closeModal();
    }

    // Ouvrir modal de suppression
    function openDeleteModal(leadId, leadName) {
        event.stopPropagation(); // Empêche le déclenchement de l'événement click sur la ligne
        deleteLeadId = leadId;
        document.getElementById('deleteLeadName').textContent = leadName;
        document.getElementById('deleteModal').style.display = 'block';
    }

    // Fermer modal de suppression
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        deleteLeadId = null;
    }

    // Confirmer suppression
    function confirmDelete() {
        alert('Lead supprimé avec succès !');
        closeDeleteModal();
    }

    // Fermer les modales en cliquant à l'extérieur
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal();
            closeDeleteModal();
        }
    }

    // Select all checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.querySelector('.select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function(e) {
                document.querySelectorAll('.select-row').forEach(cb => cb.checked = e.target.checked);
            });
        }
    });
</script>
@endsection