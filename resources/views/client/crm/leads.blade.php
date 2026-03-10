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
            <button class="btn-excel"
                onclick="window.location='{{ route('client.crm.leads.export', request()->query()) }}'">
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
                <span class="stat-value">{{ $totalLeads }}</span>
                <span class="stat-label">Total leads</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $relanceCount }}</span>
                <span class="stat-label">À relancer</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $rdvPrisCount }}</span>
                <span class="stat-label">RDV pris</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $clotureCount }}</span>
                <span class="stat-label">Clôturés</span>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('client.crm.leads') }}" id="filterForm">
        <div class="filters-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text"
                       name="search"
                       id="searchInput"
                       value="{{ request('search') }}"
                       placeholder="Rechercher un lead...">
            </div>

            <div class="filter-actions">
                <select name="scrapping" class="filter-select auto-submit">
                    <option value="">Tous les scrappings</option>
                    @foreach($scrappingNames as $scrap)
                        <option value="{{ $scrap }}" {{ request('scrapping') == $scrap ? 'selected' : '' }}>
                            {{ $scrap }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="filter-select auto-submit">
                    <option value="">Tous les statuts</option>
                    <option value="En cours" {{ request('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                    <option value="À relancer plus tard" {{ request('status') == 'À relancer plus tard' ? 'selected' : '' }}>À relancer</option>
                    <option value="RDV proposé" {{ request('status') == 'RDV proposé' ? 'selected' : '' }}>RDV proposé</option>
                    <option value="RDV pris" {{ request('status') == 'RDV pris' ? 'selected' : '' }}>RDV pris</option>
                    <option value="Refus" {{ request('status') == 'Refus' ? 'selected' : '' }}>Refus</option>
                    <option value="Clôturé" {{ request('status') == 'Clôturé' ? 'selected' : '' }}>Clôturé</option>
                </select>

                <select name="chaleur" class="filter-select auto-submit">
                    <option value="">Toute chaleur</option>
                    <option value="Froid" {{ request('chaleur') == 'Froid' ? 'selected' : '' }}>Froid</option>
                    <option value="Tiède" {{ request('chaleur') == 'Tiède' ? 'selected' : '' }}>Tiède</option>
                    <option value="Chaud" {{ request('chaleur') == 'Chaud' ? 'selected' : '' }}>Chaud</option>
                </select>

                <input type="date"
                       name="date_from"
                       class="filter-select auto-submit"
                       value="{{ request('date_from') }}"
                       placeholder="Date début">

                <input type="date"
                       name="date_to"
                       class="filter-select auto-submit"
                       value="{{ request('date_to') }}"
                       placeholder="Date fin">
            </div>
        </div>
    </form>

    <!-- Barre de redimensionnement des colonnes -->
    <div class="column-resize-bar">
        <button class="btn-reset-columns" onclick="resetColumnWidths()">
            <i class="fas fa-undo-alt"></i> Réinitialiser les colonnes
        </button>
        <span class="resize-hint">
            <i class="fas fa-arrows-alt-h"></i> Glissez les en-têtes pour redimensionner
        </span>
    </div>

    <!-- Table responsive avec colonnes redimensionnables -->
    <div class="table-responsive">
        <table class="leads-table" id="resizableTable">
            <thead>
                <tr>
                    <th class="resizable sticky-col-0" data-index="0">
                        <input type="checkbox" class="select-all">
                    </th>
                    
                    <th class="resizable sticky-col-1" data-index="1">Entreprise
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nom de l'entreprise ou de la société associée au prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable sticky-col-2" data-index="2">Prénom
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Prénom du prospect tel qu'importé ou renseigné manuellement.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="3">Nom
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nom du prospect tel qu'importé ou renseigné manuellement.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="4">Adresse postale
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Adresse du prospect tel qu'importé ou renseigné manuellement.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="5">Commentaire
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Notes internes sur le prospect (échanges, contexte, informations utiles).</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="6">Chaleur
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Niveau d'intérêt du prospect : Froid, Tiède ou Chaud.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="7">Profil LinkedIn
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Lien vers le profil LinkedIn du prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="8">Profil Facebook
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Lien vers le profil Facebook du prospect.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="9">Profil Instagram
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Lien vers le profil Instagram du prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="10">Appel Téléphonique
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Statut des appels téléphoniques effectués.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="11">Status du Lead
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">État actuel du lead dans le processus commercial.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="12">Instagram
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Statut des messages Instagram envoyés.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="13">Connexion LinkedIn
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Êtes-vous connecté sur LinkedIn avec le Lead ?</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="14">Messenger
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Statut du contact effectué via Messenger.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="15">Message Formulaire site web
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Message envoyé via le formulaire du site.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="16">Catégorie
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Métier de l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="17">Procédure de Prospection
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Étape actuelle dans la séquence de relance.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="18">Date de relance
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Date de la dernière relance effectuée.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="19">Lead à jour
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Pourcentage de complétion des informations.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="20">Devis
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Statut du devis envoyé au prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="21">MP Insta
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nombre de messages privés envoyés.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="22">Follow Insta
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Compte Instagram suivi ?</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="23">Com Insta
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Commentaires laissés sur Instagram.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="24">Formulaire
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Message via formulaire du site.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="25">Fonction
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Fonction du prospect dans l'entreprise.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="26">Email
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Email professionnel de l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="27">Email gérant
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Email direct du gérant.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="28">Tel Fixe
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Téléphone fixe de l'entreprise.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="29">Portable
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Téléphone portable du prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="30">Site web
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Site internet de l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="31">Pas de site
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">L'entreprise n'a pas de site web.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="32">Compte Insta
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nom du compte Instagram.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="33">Note
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Note attribuée à l'entreprise.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="34">Avis
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Avis sur l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="35">Scrapping
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nom de la campagne d'import.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="36">URL Maps
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Lien Google Maps de l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="37">Actions
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Actions disponibles.</span>
                        </span>
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse($leads as $lead)
                    <tr data-lead-id="{{ $lead->id }}" class="lead-row">
                        <td class="sticky-col-0" onclick="event.stopPropagation()">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td class="sticky-col-1">{{ $lead->entreprise ?? '-' }}</td>
                        <td class="sticky-col-2"><strong>{{ $lead->prenom_nom ?? '-' }}</strong></td>
                        <td><strong>{{ $lead->nom ?? '-' }}</strong></td>
                        <td>{{ $lead->adresse_postale ?? '-' }}</td>
                        <td>{{ $lead->commentaire ?? '-' }}</td>
                        <td>
                            <span class="badge status-{{ \Illuminate\Support\Str::slug($lead->chaleur) }}">
                                {{ $lead->chaleur ?? '-' }}
                            </span>
                        </td>
                        <td class="url-cell" onclick="event.stopPropagation()">
                            @if($lead->url_linkedin)
                                <a href="{{ $lead->url_linkedin }}" target="_blank" class="url-link" data-url="{{ $lead->url_linkedin }}">Voir</a>
                            @else 
                                <span class="empty-value">-</span>
                            @endif
                        </td>
                         <td class="url-cell" onclick="event.stopPropagation()">
                            @if($lead->url_facebook)
                                <a href="{{ $lead->url_facebook }}" target="_blank" class="url-link" data-url="{{ $lead->url_facebook }}">Voir</a>
                            @else 
                                <span class="empty-value">-</span>
                            @endif
                        </td>
                        <td class="url-cell" onclick="event.stopPropagation()">
                            @if($lead->url_instagramm)
                                <a href="{{ $lead->url_instagramm }}" target="_blank" class="url-link" data-url="{{ $lead->url_instagramm }}">Voir</a>
                            @else 
                                <span class="empty-value">-</span>
                            @endif
                        </td>
                        <td><span class="badge">{{ $lead->appel_tel ?? '-' }}</span></td>
                        <td><span class="badge status-{{ \Illuminate\Support\Str::slug($lead->status) }}">{{ $lead->status ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->mp_instagram ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->linkedin_status ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->messenger ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->message_form ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->categorie ?? '-' }}</span></td>
                        <td><span class="badge status-relance">{{ $lead->status_relance ?? '-' }}</span></td>
                        <td>
                            {{ $lead->date_statut ? \Carbon\Carbon::parse($lead->date_statut)->format('d/m/Y') : '-' }}
                        </td>
                        <td><span class="badge badge-percent">{{ $lead->enfants_percent ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->devis ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->mp_instagram ?? '-' }}</span></td>
                        <td class="checkbox-cell" onclick="event.stopPropagation()">
                            <input type="checkbox" disabled {{ $lead->follow_insta ? 'checked' : '' }}>
                        </td>
                        <td><span class="badge">{{ $lead->com_instagram ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->formulaire_site ?? '-' }}</span></td>
                        <td>{{ $lead->fonction ?? '-' }}</td>
                        <td>{{ $lead->email ?? '-' }}</td>
                        <td>{{ $lead->email_gerant ?? '-' }}</td>
                        <td>{{ $lead->tel_fixe ?? '-' }}</td>
                        <td>{{ $lead->portable ?? '-' }}</td>
                        <td class="url-cell" onclick="event.stopPropagation()">
                            @if($lead->url_site)
                                <a href="{{ $lead->url_site }}" target="_blank" class="url-link" data-url="{{ $lead->url_site }}">Voir</a>
                            @else 
                                <span class="empty-value">-</span>
                            @endif
                        </td>
                        <td class="checkbox-cell">
                            <input type="checkbox" disabled {{ !$lead->url_site ? 'checked' : '' }}>
                        </td>
                        <td>{{ $lead->compte_insta ?? '-' }}</td>
                        <td><span class="badge">{{ $lead->note ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->avis ?? '-' }}</span></td>
                        <td>{{ $lead->nom_global ?? '-' }}</td>
                        <td class="url-cell" onclick="event.stopPropagation()">
                            @if($lead->url_maps)
                                <a href="{{ $lead->url_maps }}" target="_blank" class="url-link" data-url="{{ $lead->url_maps }}">Voir</a>
                            @else 
                                <span class="empty-value">-</span>
                            @endif
                        </td>
                        <td class="actions" onclick="event.stopPropagation()">
                            <button class="btn-icon" onclick='openEditModal(@json($lead))' title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon" onclick="openDeleteModal({{ $lead->id }})" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn-icon" onclick="window.location='{{ route('client.crm.leads.export.single', $lead->id) }}'" title="Exporter">
                                <i class="fas fa-file-excel"></i>
                            </button>
                            <button class="btn-icon" onclick="openMailModal({{ $lead->id }})" title="Générer email IA">
                                <i class="fas fa-robot"></i>
                            </button>
                            <button class="btn-icon" onclick='openEditModal(@json($lead))' title="Ouvrir dans le modal">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="40" style="text-align:center; padding:30px;">Aucun lead trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info">
            Affichage de {{ $leads->firstItem() ?? 0 }} à {{ $leads->lastItem() ?? 0 }} sur {{ $leads->total() }} leads
        </div>
        <div class="pagination-controls">
            <button class="btn-pagination" {{ $leads->onFirstPage() ? 'disabled' : '' }}
                onclick="window.location='{{ $leads->previousPageUrl() }}'">
                <i class="fas fa-chevron-left"></i>
            </button>

            @for ($i = 1; $i <= $leads->lastPage(); $i++)
                @if ($i == $leads->currentPage())
                    <button class="btn-pagination active">{{ $i }}</button>
                @elseif ($i == 1 || $i == $leads->lastPage() || abs($i - $leads->currentPage()) <= 2)
                    <button class="btn-pagination" onclick="window.location='{{ $leads->url($i) }}'">{{ $i }}</button>
                @elseif (abs($i - $leads->currentPage()) == 3)
                    <span>...</span>
                @endif
            @endfor

            <button class="btn-pagination" {{ !$leads->hasMorePages() ? 'disabled' : '' }}
                onclick="window.location='{{ $leads->nextPageUrl() }}'">
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
            <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <form id="leadForm" method="POST" action="{{ route('client.crm.leads.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="lead_id" id="leadId">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Entreprise</label>
                        <input type="text" name="entreprise">
                    </div>

                    <div class="form-group">
                        <label>Catégorie</label>
                        <input type="text" name="categorie">
                    </div>

                    <div class="form-group">
                        <label>Adresse postale</label>
                        <input type="text" name="adresse_postale">
                    </div>

                    <div class="form-group">
                        <label>Prénom Gérant *</label>
                        <input type="text" name="prenom_nom" required>
                    </div>

                    <div class="form-group">
                        <label>Nom Gérant</label>
                        <input type="text" name="nom">
                    </div>

                    <div class="form-group">
                        <label>Commentaire</label>
                        <textarea name="commentaire" rows="2"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status du Lead</label>
                        <select name="status">
                            <option>À Contacter</option>
                            <option>En cours</option>
                            <option>À relancer plus tard</option>
                            <option>Répondu – à traiter</option>
                            <option>RDV proposé</option>
                            <option>RDV pris</option>
                            <option>Refus</option>
                            <option>Clôturé</option>
                            <option>Vendu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Procédure de Prospection</label>
                        <select name="status_relance">
                            <option>Non commencé</option>
                            <option>J0 - Mail 1</option>
                            <option>J+1 - MP 1 Insta + Follow + Like + Commentaire</option>
                            <option>J+3 - Mail 2</option>
                            <option>J+4 - WhatsApp 1 + SMS 1</option>
                            <option>J+6 - Connexion LinkedIn + MP</option>
                            <option>J+7 - Mail 3</option>
                            <option>J+8 - MP 2 Insta + Commentaire</option>
                            <option>J+10 - WhatsApp 2 + SMS 2</option>
                            <option>J+12 - Mail 4</option>
                            <option>J+15 - Appel téléphonique</option>
                            <option>J+17 - WhatsApp 3 + SMS 3</option>
                            <option>J+20 - Mail 5</option>
                            <option>RDV pris</option>
                            <option>Vendu</option>
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date du relance</label>
                        <input type="date" name="date_statut">
                    </div>

                    <div class="form-group">
                        <label>Devis</label>
                        <select name="devis">
                            <option>Pas encore</option>
                            <option>A faire</option>
                            <option>Envoyé</option>
                            <option>Validé</option>
                            <option>Perdu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Chaleur</label>
                        <select name="chaleur">
                            <option value="Pas Échangé">Pas Échangé</option>
                            <option value="Froid">Froid</option>
                            <option value="Tiède">Tiède</option>
                            <option value="Chaud">Chaud</option>
                            <option value="Mort">Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>URL Profil Facebook</label>
                        <input type="text" name="url_facebook">
                    </div>

                    <div class="form-group">
                        <label>URL Profil Instagram</label>
                        <input type="text" name="url_instagramm">
                    </div>

                    <div class="form-group">
                        <label>URL Profil LinkedIn</label>
                        <input type="text" name="url_linkedin">
                    </div>

                    <div class="form-group">
                        <label>Appel Téléphonique</label>
                        <select name="appel_tel">
                            <option>Non appelé</option>
                            <option>Message 1</option>
                            <option>Message 2</option>
                            <option>Message 3</option>
                            <option>Message 4</option>
                            <option>RDV pris</option>
                            <option>Vendu</option>
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lead à jour de ses infos</label>
                        <select name="enfants_percent">
                            <option>0%</option>
                            <option>40%</option>
                            <option>60%</option>
                            <option>80%</option>
                            <option>100%</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>LinkedIn</label>
                        <select name="linkedin_status">
                            <option>Non contacté</option>
                            <option>Validé</option>
                            <option>En attente</option>
                            <option>Connecté</option>
                            <option>Non, pas de compte</option>
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>MP Instagram</label>
                        <select name="mp_instagram">
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
                        <input type="checkbox" name="follow_insta" value="1">
                    </div>

                    <div class="form-group">
                        <label>Com Insta</label>
                        <select name="com_instagram">
                            <option>Com 1</option>
                            <option>Com 2</option>
                            <option>Com 3</option>
                            <option>Com 4</option>
                            <option>Com 5</option>
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Formulaire</label>
                        <select name="formulaire_site">
                            <option>Oui, attente réponse</option>
                            <option>Oui, avec réponse reçu</option>
                            <option>Non, Pas de Formulaire</option>
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Messenger</label>
                        <select name="messenger">
                            <option>Non contacté</option>
                            <option>Oui, attente réponse</option>
                            <option>Oui, avec réponse reçu</option>
                            <option>Non, Pas de compte</option>
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Message formulaire site web</label>
                        <select name="message_form">
                            <option>Non envoyé</option>
                            <option>À ne pas faire car déjà en relation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Fonction</label>
                        <input type="text" name="fonction">
                    </div>

                    <div class="form-group">
                        <label>Email Entreprise</label>
                        <input type="email" name="email">
                    </div>

                    <div class="form-group">
                        <label>Email Gérant</label>
                        <input type="email" name="email_gerant">
                    </div>

                    <div class="form-group">
                        <label>Téléphone Fixe</label>
                        <input type="text" name="tel_fixe">
                    </div>

                    <div class="form-group">
                        <label>Téléphone Portable</label>
                        <input type="text" name="portable">
                    </div>

                    <div class="form-group">
                        <label>URL Maps</label>
                        <input type="text" name="url_maps">
                    </div>

                    <div class="form-group">
                        <label>URL Site</label>
                        <input type="text" name="url_site">
                    </div>

                    <div class="form-group">
                        <label>Compte Insta</label>
                        <input type="text" name="compte_insta">
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <input type="text" name="note">
                    </div>

                    <div class="form-group">
                        <label>Avis</label>
                        <input type="text" name="avis">
                    </div>

                    <div class="form-group">
                        <label>Nom du scrapping</label>
                        <input type="text" name="nom_global">
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
            <button type="button" class="close-btn" onclick="closeDeleteModal()">&times;</button>
        </div>

        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer ce lead ?</p>
        </div>

        <div class="modal-footer">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                <button type="submit" class="btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal IA -->
<div id="mailModal" class="modal">
    <div class="modal-content" style="max-width:800px;">
        <div class="modal-header">
            <h2>Génération d'emails IA</h2>
            <button class="close-btn" onclick="closeMailModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div id="mailLoader" style="display:none;text-align:center;">
                <p>Génération en cours...</p>
            </div>
            <div id="mailResults" style="white-space:pre-wrap;"></div>
        </div>

        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeMailModal()">Fermer</button>
        </div>
    </div>
</div>

<style>
/* =========================================
   STYLES GLOBAUX AMÉLIORÉS
========================================= */

/* Import Google Font */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

* {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* Premium Card Style */
.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 30px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    padding: 28px;
    margin: 20px;
    border: 1px solid rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
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
    font-size: 32px;
    font-weight: 800;
    background: linear-gradient(135deg, #1a2639, #2d3b55);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0 0 4px 0;
    letter-spacing: -0.5px;
}

.header-left p {
    color: #4a5568;
    font-size: 15px;
    margin: 0;
    font-weight: 400;
}

.header-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* Button Styles */
.btn-excel {
    background: linear-gradient(135deg, #059669, #10b981);
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
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

.btn-excel:hover {
    background: linear-gradient(135deg, #047857, #059669);
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(5, 150, 105, 0.4);
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
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-add:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.4);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff, #f7fafc);
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
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
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
.stat-icon.green { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
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
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
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
    transition: all 0.2s;
}

.search-box:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
    background: transparent;
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
    transition: all 0.2s;
}

.filter-select:hover {
    border-color: #94a3b8;
}

.filter-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Column Resize Bar */
.column-resize-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background: linear-gradient(135deg, #eef2f6, #e9edf2);
    border-radius: 12px;
    margin-bottom: 16px;
    font-size: 13px;
}

.btn-reset-columns {
    background: white;
    border: 1px solid #e2e8f0;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
    color: #4a5568;
    transition: all 0.2s;
    font-size: 12px;
}

.btn-reset-columns:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}

.resize-hint {
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Premium Table */
.table-responsive {
    position: relative;
    overflow-x: auto;
    margin: 0 -28px;
    padding: 0 28px;
    border-radius: 20px;
    max-width: 100%;
}

.leads-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 2500px;
    font-size: 13px;
    table-layout: auto;
}

/* Colonnes redimensionnables */
.leads-table th.resizable {
    position: relative;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    color: #1e293b;
    font-size: 12px;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #d1d5db;
    white-space: nowrap;
    user-select: none;
    cursor: col-resize;
}

.leads-table th.resizable::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 4px;
    background: transparent;
    cursor: col-resize;
    transition: background 0.2s;
}

.leads-table th.resizable:hover::after {
    background: rgba(59, 130, 246, 0.3);
}

.leads-table th.resizable:active::after {
    background: rgba(59, 130, 246, 0.5);
}

.leads-table td {
    padding: 16px 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    vertical-align: middle;
    transition: background 0.2s;
}

.leads-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
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
    transition: all 0.2s;
}

.badge:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Couleurs des badges */
.badge.status-chaud,
.badge.chaud { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fecaca; }

.badge.status-tiede,
.badge.tiede { background: linear-gradient(135deg, #fff3cd, #ffe69c); color: #856404; border: 1px solid #ffe69c; }

.badge.status-froid,
.badge.froid { background: linear-gradient(135deg, #e2e3e5, #d3d6d8); color: #383d41; border: 1px solid #d3d6d8; }

.badge.status-encours,
.badge.encours { background: linear-gradient(135deg, #cfe2ff, #b6d4fe); color: #052c65; border: 1px solid #b6d4fe; }

.badge.status-relance,
.badge.relance { background: linear-gradient(135deg, #fff3cd, #ffe69c); color: #856404; border: 1px solid #ffe69c; }

.badge.status-rdv-pris,
.badge.rdv-pris { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }

.badge.status-refus,
.badge.refus { background: linear-gradient(135deg, #f8d7da, #f5c2c7); color: #842029; border: 1px solid #f5c2c7; }

.badge.badge-percent,
.badge.pourcent { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }

.badge.attente { background: linear-gradient(135deg, #cff4fc, #b6effb); color: #055160; border: 1px solid #b6effb; }
.badge.valide { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
.badge.envoye { background: linear-gradient(135deg, #cfe2ff, #b6d4fe); color: #052c65; border: 1px solid #b6d4fe; }

/* Actions */
.actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.btn-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    border-radius: 10px;
    color: #5f6b7a;
    transition: all 0.2s ease;
    font-size: 14px;
    flex-shrink: 0;
}

.btn-icon:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    color: #0f172a;
    border-color: #94a3b8;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

/* Checkbox */
.select-all, .select-row {
    width: 18px;
    height: 18px;
    border-radius: 5px;
    border: 2px solid #cbd5e1;
    cursor: pointer;
    accent-color: #3b82f6;
}

.checkbox-cell {
    text-align: center;
}

/* URL Links */
.url-cell {
    max-width: 150px;
}

.url-cell a {
    color: #2563eb;
    text-decoration: none;
    font-size: 12px;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    transition: color 0.2s;
}

.url-cell a:hover {
    color: #1d4ed8;
    text-decoration: underline;
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
    font-weight: 500;
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
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-color: #94a3b8;
    transform: translateY(-1px);
}

.btn-pagination.active {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-color: #2563eb;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
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
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    overflow-y: auto;
    padding: 20px;
    backdrop-filter: blur(8px);
}

.modal-content {
    background: white;
    border-radius: 30px;
    max-width: 900px;
    margin: 20px auto;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: modalFadeIn 0.3s ease;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 24px 28px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 30px 30px 0 0;
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
    transform: rotate(90deg);
}

.modal-body {
    padding: 28px;
    max-height: 60vh;
    overflow-y: auto;
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
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 0 0 30px 30px;
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
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.4);
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
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.4);
}

/* Tooltip colonne */
.info-tooltip {
    position: relative;
    display: inline-block;
    margin-left: 4px;
    cursor: pointer;
    color: #94a3b8;
}

.info-tooltip i {
    font-size: 11px;
}

.info-tooltip .tooltip-text {
    visibility: hidden;
    opacity: 0;
    position: absolute;
    top: 125%;
    left: 50%;
    transform: translateX(-50%);
    background: #1e293b;
    color: #fff;
    padding: 6px 8px;
    border-radius: 6px;
    font-size: 11px;
    white-space: nowrap;
    transition: opacity 0.15s ease;
    z-index: 999;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.info-tooltip .tooltip-text::after {
    content: "";
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 4px;
    border-style: solid;
    border-color: transparent transparent #1e293b transparent;
}

.info-tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

/* =========================================
   STICKY COLUMNS
========================================= */

.leads-table th.sticky-col-0,
.leads-table th.sticky-col-1,
.leads-table th.sticky-col-2 {
    position: sticky;
    top: 0;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    z-index: 40;
}

.leads-table td.sticky-col-0,
.leads-table td.sticky-col-1,
.leads-table td.sticky-col-2 {
    position: sticky;
    background: white;
    z-index: 30;
}

.leads-table th.sticky-col-0,
.leads-table td.sticky-col-0 {
    text-align: center;
}

.leads-table tbody tr:hover td.sticky-col-0,
.leads-table tbody tr:hover td.sticky-col-1,
.leads-table tbody tr:hover td.sticky-col-2 {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
}

.leads-table th.sticky-col-2,
.leads-table td.sticky-col-2 {
    box-shadow: 6px 0 12px -6px rgba(0, 0, 0, 0.15);
}

/* =========================================
   ACTIONS COLUMN FIX
========================================= */

.leads-table th[data-index="37"],
.leads-table td.actions {
    min-width: 200px;
    width: 200px;
}

.leads-table td.actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
}

/* Colonnes Entreprise + Prénom */
.leads-table th:nth-child(2),
.leads-table td:nth-child(2) {
    min-width: 250px;
    max-width: 350px;
}

.leads-table th:nth-child(3),
.leads-table td:nth-child(3) {
    min-width: 150px;
    max-width: 200px;
}

/* =========================================
   INLINE EDIT STYLES
========================================= */

.editable-cell {
    cursor: pointer;
    position: relative;
    transition: background-color 0.2s;
}

.editable-cell:hover {
    background-color: #f0f9ff !important;
    box-shadow: inset 0 0 0 2px #3b82f6;
}

.editable-cell.editing {
    padding: 0 !important;
    background-color: white !important;
    box-shadow: 0 0 0 3px #3b82f6;
    z-index: 100;
    position: relative;
}

.inline-input, .inline-select, .inline-textarea {
    width: 100%;
    height: 100%;
    min-height: 40px;
    padding: 8px 12px;
    border: none;
    outline: none;
    font-size: 13px;
    font-family: inherit;
    background: white;
    box-sizing: border-box;
    border-radius: 0;
}

.inline-select {
    cursor: pointer;
    background: white;
}

.inline-textarea {
    resize: vertical;
    min-height: 60px;
}

/* Notification */
.edit-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 24px;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    font-weight: 500;
    z-index: 9999;
    animation: slideIn 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.edit-notification.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.edit-notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Toggle switch */
.edit-mode-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: 16px;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
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
        flex-direction: column;
    }

    .filter-select {
        width: 100%;
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

    .column-resize-bar {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
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

/* Style amélioré pour l'édition d'URL */
.url-edit {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #3b82f6;
    border-radius: 6px;
    font-size: 12px;
    outline: none;
    background: white;
    font-family: 'Inter', sans-serif;
}

.url-edit:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    border-color: #2563eb;
}

.url-edit::placeholder {
    color: #94a3b8;
    font-style: italic;
}

/* Amélioration du lien Voir */
.url-link {
    color: #2563eb;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    padding: 4px 12px;
    background: #eff6ff;
    border-radius: 20px;
    transition: all 0.2s;
    cursor: pointer;
    border: 1px solid #bfdbfe;
}

.url-link:hover {
    background: #dbeafe;
    color: #1d4ed8;
    transform: translateY(-1px);
    border-color: #3b82f6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.empty-value {
    color: #94a3b8;
    font-style: italic;
    font-size: 12px;
    padding: 4px 8px;
    background: #f8fafc;
    border-radius: 4px;
}

/* Tooltip pour montrer l'URL complète au survol */
.url-cell {
    position: relative;
}

.url-cell:hover .url-link::after {
    content: attr(data-url);
    position: absolute;
    bottom: 100%;
    left: 0;
    background: #1e293b;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    border: 1px solid #334155;
}

.url-cell:hover .url-link::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 20px;
    border-width: 5px;
    border-style: solid;
    border-color: #1e293b transparent transparent transparent;
    z-index: 1000;
    margin-bottom: -2px;
}

/* Indicateur visuel que la cellule est cliquable */
.url-cell:not(.editing):hover {
    background-color: #f0f9ff;
    cursor: pointer;
}

.url-cell:not(.editing):hover .url-link {
    background: #dbeafe;
}
</style>

<script>
/*
|--------------------------------------------------------------------------
| VARIABLES GLOBALES
|--------------------------------------------------------------------------
*/
let currentLeadId = null;

const leadModal = document.getElementById('leadModal');
const deleteModal = document.getElementById('deleteModal');
const leadForm = document.getElementById('leadForm');
const deleteForm = document.getElementById('deleteForm');
const modalTitle = document.getElementById('modalTitle');
const formMethod = document.getElementById('formMethod');
const mailModal = document.getElementById('mailModal');

/*
|--------------------------------------------------------------------------
| REDIMENSIONNEMENT DES COLONNES
|--------------------------------------------------------------------------
*/
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('resizableTable');
    const headers = table.querySelectorAll('th.resizable');
    let isResizing = false;
    let currentHeader = null;
    let startX = 0;
    let startWidth = 0;

    // Charger les largeurs sauvegardées
    loadColumnWidths();

    headers.forEach(header => {
        header.addEventListener('mousedown', function(e) {
            if (e.offsetX > this.offsetWidth - 10) {
                isResizing = true;
                currentHeader = this;
                startX = e.pageX;
                startWidth = this.offsetWidth;
                
                document.body.style.cursor = 'col-resize';
                document.body.style.userSelect = 'none';
                
                e.preventDefault();
            }
        });
    });

    document.addEventListener('mousemove', function(e) {
        if (!isResizing || !currentHeader) return;

        const width = startWidth + (e.pageX - startX);
        if (width > 50) {
            currentHeader.style.width = width + 'px';
            
            const index = currentHeader.dataset.index;
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cell = row.cells[index];
                if (cell) {
                    cell.style.width = width + 'px';
                }
            });
        }
    });

    document.addEventListener('mouseup', function() {
        if (isResizing && currentHeader) {
            isResizing = false;
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
            saveColumnWidths();
            currentHeader = null;
        }
    });

    function saveColumnWidths() {
        const widths = [];
        headers.forEach(header => {
            widths.push(header.style.width || header.offsetWidth + 'px');
        });
        localStorage.setItem('columnWidths', JSON.stringify(widths));
    }

    function loadColumnWidths() {
        const saved = localStorage.getItem('columnWidths');
        if (saved) {
            const widths = JSON.parse(saved);
            headers.forEach((header, index) => {
                if (widths[index]) {
                    header.style.width = widths[index];
                    
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const cell = row.cells[index];
                        if (cell) {
                            cell.style.width = widths[index];
                        }
                    });
                }
            });
        }
    }

    window.resetColumnWidths = function() {
        headers.forEach((header, index) => {
            header.style.width = '';
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cell = row.cells[index];
                if (cell) {
                    cell.style.width = '';
                }
            });
        });
        localStorage.removeItem('columnWidths');
    };
});

/*
|--------------------------------------------------------------------------
| FONCTIONS MODALES
|--------------------------------------------------------------------------
*/
function openAddModal() {
    currentLeadId = null;
    modalTitle.textContent = "Ajouter un lead";
    leadForm.action = "{{ route('client.crm.leads.store') }}";
    formMethod.value = "POST";
    leadForm.reset();
    leadModal.style.display = "block";
}

function openEditModal(lead) {
    currentLeadId = lead.id;
    modalTitle.textContent = "Modifier le lead";
    leadForm.action = "/crm/leads/" + lead.id;
    formMethod.value = "PUT";

    Object.keys(lead).forEach(function(key) {
        const field = leadForm.querySelector(`[name="${key}"]`);
        if (field) {
            if (field.type === "checkbox") {
                field.checked = lead[key] ? true : false;
            } else {
                field.value = lead[key] ?? '';
            }
        }
    });

    leadModal.style.display = "block";
}

function closeModal() {
    leadModal.style.display = "none";
}

function openDeleteModal(leadId) {
    deleteForm.action = "/crm/leads/" + leadId;
    deleteModal.style.display = "block";
}

function closeDeleteModal() {
    deleteModal.style.display = "none";
}

function openMailModal(leadId) {
    mailModal.style.display = "block";
    document.getElementById('mailLoader').style.display = "block";
    document.getElementById('mailResults').innerHTML = "";

    fetch(`/crm/leads/${leadId}/generate-mails`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('mailLoader').style.display = "none";
        document.getElementById('mailResults').innerText = data.content;
    })
    .catch(err => {
        document.getElementById('mailLoader').style.display = "none";
        document.getElementById('mailResults').innerText = "Erreur lors de la génération.";
    });
}

function closeMailModal() {
    mailModal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target.classList.contains("modal")) {
        closeModal();
        closeDeleteModal();
        closeMailModal();
    }
};

/*
|--------------------------------------------------------------------------
| SELECT ALL CHECKBOX
|--------------------------------------------------------------------------
*/
document.addEventListener("DOMContentLoaded", function() {
    const selectAll = document.querySelector(".select-all");
    if (selectAll) {
        selectAll.addEventListener("change", function(e) {
            document.querySelectorAll(".select-row").forEach(cb => cb.checked = e.target.checked);
        });
    }
});

/*
|--------------------------------------------------------------------------
| AUTO SUBMIT FILTERS
|--------------------------------------------------------------------------
*/
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("filterForm");

    document.querySelectorAll(".auto-submit").forEach(element => {
        element.addEventListener("change", function() {
            form.submit();
        });
    });

    const searchInput = document.getElementById("searchInput");
    let timeout = null;

    searchInput.addEventListener("keyup", function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            form.submit();
        }, 500);
    });
});

/*
|--------------------------------------------------------------------------
| STICKY COLUMNS POSITIONS
|--------------------------------------------------------------------------
*/
function updateStickyPositions() {
    const table = document.getElementById('resizableTable');
    if (!table) return;

    const th0 = table.querySelector('th.sticky-col-0');
    const th1 = table.querySelector('th.sticky-col-1');

    if (!th0 || !th1) return;

    const width0 = th0.offsetWidth;
    const width1 = th1.offsetWidth;

    const col0 = table.querySelectorAll('.sticky-col-0');
    const col1 = table.querySelectorAll('.sticky-col-1');
    const col2 = table.querySelectorAll('.sticky-col-2');

    col0.forEach(el => el.style.left = "0px");
    col1.forEach(el => el.style.left = width0 + "px");
    col2.forEach(el => el.style.left = (width0 + width1) + "px");
}

document.addEventListener("DOMContentLoaded", updateStickyPositions);
window.addEventListener("resize", updateStickyPositions);
document.addEventListener("mouseup", updateStickyPositions);

/*
|--------------------------------------------------------------------------
| ÉDITION INLINE (COMME AIRTABLE)
|--------------------------------------------------------------------------
*/

const inlineEdit = {
    activeCell: null,
    originalValue: null,
    inputTypes: {
        text: [1, 2, 3, 4, 5, 16, 25, 26, 27, 28, 29, 32, 33, 34, 35],
        select: [6, 10, 11, 12, 13, 14, 15, 17, 19, 20, 21, 23, 24],
        date: [18],
        checkbox: [22, 31],
        url: [7, 8, 9, 30, 36]
    },
    
    selectOptions: {
        6: ['Pas Échangé', 'Froid', 'Tiède', 'Chaud', 'Mort'],
        10: ['Non appelé', 'Message 1', 'Message 2', 'Message 3', 'Message 4', 'RDV pris', 'Vendu', 'Mort'],
        11: ['À Contacter', 'En cours', 'À relancer plus tard', 'Répondu – à traiter', 'RDV proposé', 'RDV pris', 'Refus', 'Clôturé', 'Vendu'],
        12: ['MP 1', 'MP 2', 'MP 3', 'MP 4', 'MP 5', 'Mort'],
        13: ['Non contacté', 'Validé', 'En attente', 'Connecté', 'Non, pas de compte', 'Mort'],
        14: ['Non contacté', 'Oui, attente réponse', 'Oui, avec réponse reçu', 'Non, Pas de compte', 'Mort'],
        15: ['Non envoyé', 'À ne pas faire car déjà en relation'],
        17: ['Non commencé', 'J0 - Mail 1', 'J+1 - MP 1 Insta + Follow + Like + Commentaire', 'J+3 - Mail 2', 'J+4 - WhatsApp 1 + SMS 1', 'J+6 - Connexion LinkedIn + MP', 'J+7 - Mail 3', 'J+8 - MP 2 Insta + Commentaire', 'J+10 - WhatsApp 2 + SMS 2', 'J+12 - Mail 4', 'J+15 - Appel téléphonique', 'J+17 - WhatsApp 3 + SMS 3', 'J+20 - Mail 5', 'RDV pris', 'Vendu', 'Mort'],
        19: ['0%', '40%', '60%', '80%', '100%'],
        20: ['Pas encore', 'A faire', 'Envoyé', 'Validé', 'Perdu'],
        21: ['MP 1', 'MP 2', 'MP 3', 'MP 4', 'MP 5', 'Mort'],
        23: ['Com 1', 'Com 2', 'Com 3', 'Com 4', 'Com 5', 'Mort'],
        24: ['Oui, attente réponse', 'Oui, avec réponse reçu', 'Non, Pas de Formulaire', 'Mort']
    }
};

// Activer l'édition inline
document.addEventListener('DOMContentLoaded', function() {
    enableInlineEditing();
    addEditModeToggle();
    addCsrfMeta();
});

function enableInlineEditing() {
    const table = document.getElementById('resizableTable');
    if (!table) return;
    
    const cells = table.querySelectorAll('tbody td');
    
    cells.forEach(cell => {
        if (cell.classList.contains('actions') || 
            cell.classList.contains('sticky-col-0') ||
            cell.querySelector('input[type="checkbox"]')) {
            return;
        }
        
        cell.removeEventListener('dblclick', startInlineEditHandler);
        cell.removeEventListener('click', stopPropagationHandler);
        
        cell.addEventListener('dblclick', startInlineEditHandler);
        cell.addEventListener('click', stopPropagationHandler);
        
        cell.classList.add('editable-cell');
        cell.setAttribute('title', 'Double-cliquer pour modifier');
    });
}

function stopPropagationHandler(e) {
    e.stopPropagation();
}

function startInlineEditHandler(e) {
    e.stopPropagation();
    startInlineEdit(this);
}

function startInlineEdit(cell) {
    if (inlineEdit.activeCell === cell) return;
    
    if (inlineEdit.activeCell) {
        cancelInlineEdit();
    }
    
    const columnIndex = cell.cellIndex;
    const originalText = cell.innerText.trim();
    const row = cell.closest('tr');
    const leadId = row.getAttribute('data-lead-id');
    
    if (!leadId) return;
    
    inlineEdit.activeCell = cell;
    inlineEdit.originalValue = originalText;
    
    const input = createInputElement(columnIndex, originalText, leadId);
    
    cell.innerHTML = '';
    cell.appendChild(input);
    cell.classList.add('editing');
    
    if (input.tagName === 'INPUT' || input.tagName === 'SELECT' || input.tagName === 'TEXTAREA') {
        input.focus();
        if (input.type === 'text' || input.type === 'url') {
            input.select();
        }
    }
    
    setupInputEvents(input, cell, leadId, columnIndex);
}

function createInputElement(columnIndex, value, leadId) {
    let input;
    let cleanValue = value === '-' ? '' : value;
    
    if (inlineEdit.inputTypes.checkbox.includes(columnIndex)) {
        input = document.createElement('input');
        input.type = 'checkbox';
        input.checked = value === 'Oui' || value === '1' || value === true;
        input.style.transform = 'scale(1.5)';
        input.style.margin = '0 auto';
        input.style.display = 'block';
    } 
    else if (inlineEdit.inputTypes.select.includes(columnIndex)) {
        input = document.createElement('select');
        input.className = 'inline-select';
        
        const options = inlineEdit.selectOptions[columnIndex] || [];
        
        const emptyOption = document.createElement('option');
        emptyOption.value = '';
        emptyOption.textContent = '-';
        input.appendChild(emptyOption);
        
        options.forEach(optValue => {
            const option = document.createElement('option');
            option.value = optValue;
            option.textContent = optValue;
            option.selected = (optValue === cleanValue);
            input.appendChild(option);
        });
    }
    else if (inlineEdit.inputTypes.date.includes(columnIndex)) {
        input = document.createElement('input');
        input.type = 'date';
        input.value = convertToDate(cleanValue);
        input.className = 'inline-input';
    }
    else if (inlineEdit.inputTypes.url.includes(columnIndex)) {
        input = document.createElement('input');
        input.type = 'url';
        input.value = cleanValue;
        input.placeholder = 'https://...';
        input.className = 'inline-input';
    }
    else if (columnIndex === 5) {
        input = document.createElement('textarea');
        input.value = cleanValue;
        input.rows = 3;
        input.className = 'inline-textarea';
        input.style.width = '100%';
        input.style.padding = '8px';
    }
    else {
        input = document.createElement('input');
        input.type = 'text';
        input.value = cleanValue;
        input.className = 'inline-input';
    }
    
    input.setAttribute('data-column', columnIndex);
    input.setAttribute('data-lead-id', leadId);
    
    return input;
}

function setupInputEvents(input, cell, leadId, columnIndex) {
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && input.tagName !== 'TEXTAREA') {
            e.preventDefault();
            saveInlineEdit(cell, leadId, columnIndex);
        }
        if (e.key === 'Escape') {
            cancelInlineEdit();
        }
    });
    
    input.addEventListener('blur', function() {
        setTimeout(() => {
            if (inlineEdit.activeCell === cell) {
                saveInlineEdit(cell, leadId, columnIndex);
            }
        }, 200);
    });
    
    input.addEventListener('click', e => e.stopPropagation());
    input.addEventListener('dblclick', e => e.stopPropagation());
}

function saveInlineEdit(cell, leadId, columnIndex) {
    const input = cell.querySelector('input, select, textarea');
    if (!input) return;
    
    let newValue;
    
    if (input.type === 'checkbox') {
        newValue = input.checked ? 'Oui' : 'Non';
    } else {
        newValue = input.value.trim() || '-';
    }
    
    updateCellDisplay(cell, newValue, columnIndex);
    saveToDatabase(leadId, getColumnName(columnIndex), newValue);
    
    inlineEdit.activeCell = null;
}

function cancelInlineEdit() {
    if (!inlineEdit.activeCell) return;
    
    const cell = inlineEdit.activeCell;
    cell.innerHTML = inlineEdit.originalValue;
    cell.classList.remove('editing');
    inlineEdit.activeCell = null;
}

function updateCellDisplay(cell, value, columnIndex) {
    if (inlineEdit.inputTypes.select.includes(columnIndex)) {
        const badgeClass = value.toLowerCase().replace(/\s+/g, '-');
        cell.innerHTML = `<span class="badge status-${badgeClass}">${value}</span>`;
    }
    else if (inlineEdit.inputTypes.url.includes(columnIndex) && value !== '-') {
        cell.innerHTML = `<a href="${value}" target="_blank" onclick="event.stopPropagation()">Voir</a>`;
    }
    else if (columnIndex === 22 || columnIndex === 31) {
        const isChecked = value === 'Oui' || value === '1' || value === true;
        cell.innerHTML = `<input type="checkbox" disabled ${isChecked ? 'checked' : ''}>`;
    }
    else {
        cell.innerHTML = value;
    }
}

function saveToDatabase(leadId, fieldName, value) {
    if (!fieldName) return;
    
    fetch(`/crm/leads/${leadId}/inline-update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            field: fieldName,
            value: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('✓ Modification enregistrée', 'success');
        } else {
            showNotification('✗ Erreur lors de la sauvegarde', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('✗ Erreur de connexion', 'error');
    });
}

function getColumnName(columnIndex) {
    const mapping = {
        1: 'entreprise',
        2: 'prenom_nom',
        3: 'nom',
        4: 'adresse_postale',
        5: 'commentaire',
        6: 'chaleur',
        7: 'url_linkedin',
        8: 'url_facebook',
        9: 'url_instagramm',
        10: 'appel_tel',
        11: 'status',
        12: 'mp_instagram',
        13: 'linkedin_status',
        14: 'messenger',
        15: 'message_form',
        16: 'categorie',
        17: 'status_relance',
        18: 'date_statut',
        19: 'enfants_percent',
        20: 'devis',
        21: 'mp_instagram',
        22: 'follow_insta',
        23: 'com_instagram',
        24: 'formulaire_site',
        25: 'fonction',
        26: 'email',
        27: 'email_gerant',
        28: 'tel_fixe',
        29: 'portable',
        30: 'url_site',
        31: 'pas_de_site',
        32: 'compte_insta',
        33: 'note',
        34: 'avis',
        35: 'nom_global',
        36: 'url_maps'
    };
    return mapping[columnIndex] || null;
}

function convertToDate(dateStr) {
    if (!dateStr || dateStr === '-') return '';
    try {
        const parts = dateStr.split('/');
        if (parts.length === 3) {
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        return '';
    } catch {
        return '';
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `edit-notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

function addEditModeToggle() {
    const headerActions = document.querySelector('.header-actions');
    if (!headerActions) return;
    
    if (document.getElementById('inlineEditToggle')) return;
    
    const toggle = document.createElement('div');
    toggle.className = 'edit-mode-toggle';
    toggle.innerHTML = `
        <span style="font-size: 13px; color: #4a5568;">Édition inline</span>
        <label class="toggle-switch">
            <input type="checkbox" id="inlineEditToggle" checked>
            <span class="toggle-slider"></span>
        </label>
    `;
    
    headerActions.appendChild(toggle);
    
    document.getElementById('inlineEditToggle').addEventListener('change', function(e) {
        if (e.target.checked) {
            enableInlineEditing();
            showNotification('Mode édition inline activé', 'success');
        } else {
            disableInlineEditing();
            showNotification('Mode modal activé', 'success');
        }
    });
}

function disableInlineEditing() {
    const table = document.getElementById('resizableTable');
    if (!table) return;
    
    const cells = table.querySelectorAll('tbody td');
    
    cells.forEach(cell => {
        cell.classList.remove('editable-cell');
        cell.removeAttribute('title');
        cell.removeEventListener('dblclick', startInlineEditHandler);
        cell.removeEventListener('click', stopPropagationHandler);
    });
}

function addCsrfMeta() {
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
}
/*
|--------------------------------------------------------------------------
| GESTION AMÉLIORÉE DES LIENS
|--------------------------------------------------------------------------
*/
document.addEventListener('DOMContentLoaded', function() {
    // Gérer les clics sur les liens "Voir"
    document.querySelectorAll('.url-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.stopPropagation(); // Empêcher la propagation vers la cellule
        });
    });
});

// Surcharger la fonction startInlineEdit pour gérer correctement les URLs
function startInlineEdit(cell) {
    if (inlineEdit.activeCell === cell) return;
    
    if (inlineEdit.activeCell) {
        cancelInlineEdit();
    }
    
    const columnIndex = cell.cellIndex;
    const row = cell.closest('tr');
    const leadId = row.getAttribute('data-lead-id');
    
    if (!leadId) return;
    
    // Récupérer la valeur actuelle correctement
    let currentValue = '';
    const link = cell.querySelector('.url-link');
    
    if (link) {
        // Si c'est un lien, on récupère l'URL depuis l'attribut href ou data-url
        currentValue = link.getAttribute('data-url') || link.getAttribute('href');
    } else {
        // Si c'est un tiret ou du texte, on récupère le texte
        const text = cell.innerText.trim();
        currentValue = text === '-' ? '' : text;
    }
    
    inlineEdit.activeCell = cell;
    inlineEdit.originalValue = currentValue || '-';
    
    // Créer l'élément d'édition approprié
    let input;
    
    if (inlineEdit.inputTypes.url.includes(columnIndex)) {
        // Pour les URLs, créer un input de type url
        input = document.createElement('input');
        input.type = 'url';
        input.value = currentValue || '';
        input.placeholder = 'https://...';
        input.className = 'inline-input url-edit';
    } else {
        // Pour les autres types, utiliser la fonction existante
        input = createInputElement(columnIndex, currentValue, leadId);
    }
    
    input.setAttribute('data-column', columnIndex);
    input.setAttribute('data-lead-id', leadId);
    
    // Vider la cellule et ajouter l'input
    cell.innerHTML = '';
    cell.appendChild(input);
    cell.classList.add('editing');
    
    // Focus et sélection
    if (input.tagName === 'INPUT' || input.tagName === 'SELECT' || input.tagName === 'TEXTAREA') {
        input.focus();
        if (input.type === 'text' || input.type === 'url') {
            input.select();
        }
    }
    
    // Gestionnaires d'événements
    setupInputEvents(input, cell, leadId, columnIndex);
}

// Surcharger setupInputEvents pour gérer la sauvegarde des URLs
function setupInputEvents(input, cell, leadId, columnIndex) {
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && input.tagName !== 'TEXTAREA') {
            e.preventDefault();
            saveInlineEdit(cell, leadId, columnIndex);
        }
        if (e.key === 'Escape') {
            cancelInlineEdit();
        }
    });
    
    input.addEventListener('blur', function() {
        setTimeout(() => {
            if (inlineEdit.activeCell === cell) {
                saveInlineEdit(cell, leadId, columnIndex);
            }
        }, 200);
    });
    
    input.addEventListener('click', e => e.stopPropagation());
    input.addEventListener('dblclick', e => e.stopPropagation());
}

// Surcharger saveInlineEdit pour gérer les URLs correctement
function saveInlineEdit(cell, leadId, columnIndex) {
    const input = cell.querySelector('input, select, textarea');
    if (!input) return;
    
    let newValue;
    
    if (input.type === 'checkbox') {
        newValue = input.checked ? 'Oui' : 'Non';
    } else {
        newValue = input.value.trim();
    }
    
    // Si c'est une URL et que la valeur est vide, mettre '-'
    if (inlineEdit.inputTypes.url.includes(columnIndex)) {
        newValue = newValue || '-';
        
        // Formater l'URL si elle n'est pas vide
        if (newValue !== '-' && newValue !== '') {
            if (!newValue.startsWith('http://') && !newValue.startsWith('https://')) {
                newValue = 'https://' + newValue;
            }
        }
    } else {
        newValue = newValue || '-';
    }
    
    // Mettre à jour l'affichage
    if (inlineEdit.inputTypes.url.includes(columnIndex) && newValue !== '-') {
        cell.innerHTML = `<a href="${newValue}" target="_blank" class="url-link" data-url="${newValue}" onclick="event.stopPropagation()">Voir</a>`;
    } else if (inlineEdit.inputTypes.select.includes(columnIndex)) {
        const badgeClass = newValue.toLowerCase().replace(/\s+/g, '-');
        cell.innerHTML = `<span class="badge status-${badgeClass}" onclick="event.stopPropagation()">${newValue}</span>`;
    } else if (columnIndex === 22 || columnIndex === 31) {
        const isChecked = newValue === 'Oui' || newValue === '1' || newValue === true;
        cell.innerHTML = `<input type="checkbox" disabled ${isChecked ? 'checked' : ''} onclick="event.stopPropagation()">`;
    } else {
        cell.innerHTML = newValue;
    }
    
    // Sauvegarder dans la base de données
    const fieldName = getColumnName(columnIndex);
    let valueToSave = newValue;
    
    // Ne pas sauvegarder le tiret pour les URLs
    if (inlineEdit.inputTypes.url.includes(columnIndex) && newValue === '-') {
        valueToSave = '';
    }
    
    saveToDatabase(leadId, fieldName, valueToSave);
    
    inlineEdit.activeCell = null;
}

// Ajouter aussi une fonction pour annuler correctement
function cancelInlineEdit() {
    if (!inlineEdit.activeCell) return;
    
    const cell = inlineEdit.activeCell;
    const columnIndex = cell.cellIndex;
    const originalValue = inlineEdit.originalValue;
    
    // Restaurer l'affichage original
    if (inlineEdit.inputTypes.url.includes(columnIndex) && originalValue && originalValue !== '-') {
        cell.innerHTML = `<a href="${originalValue}" target="_blank" class="url-link" data-url="${originalValue}" onclick="event.stopPropagation()">Voir</a>`;
    } else if (inlineEdit.inputTypes.select.includes(columnIndex) && originalValue && originalValue !== '-') {
        const badgeClass = originalValue.toLowerCase().replace(/\s+/g, '-');
        cell.innerHTML = `<span class="badge status-${badgeClass}" onclick="event.stopPropagation()">${originalValue}</span>`;
    } else if (columnIndex === 22 || columnIndex === 31) {
        const isChecked = originalValue === 'Oui' || originalValue === '1' || originalValue === true;
        cell.innerHTML = `<input type="checkbox" disabled ${isChecked ? 'checked' : ''} onclick="event.stopPropagation()">`;
    } else {
        cell.innerHTML = originalValue;
    }
    
    cell.classList.remove('editing');
    inlineEdit.activeCell = null;
}
</script>
@endsection