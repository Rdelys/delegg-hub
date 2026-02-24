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
                <!-- Nouveau filtre par nom de scrapping -->
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
                    <th class="resizable" data-index="0">
                        <input type="checkbox" class="select-all">
                    </th>
                    
                    <th class="resizable" data-index="1">Entreprise
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nom de l'entreprise ou de la société associée au prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="2">Prénom
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
                            <span class="tooltip-text">Numéro du Lead ?</span>
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
                            <span class="tooltip-text">Status du lead sur le liaison sur Instagram</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="13">Connexion LinkedIn
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Est ce que vous êtes connecté sur Linkedin avec le Lead ?</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="14">Messenger
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Statut du contact effectué via Messenger (envoyé, réponse reçue ou indisponible).</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="15">Message Formulaire site web
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Lead effectué via Messenger (envoyé, réponse reçue ou deja en relation).</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="16">Catégorie
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Métier de l'entreprise</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="17">Procédure de Prospection
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Étape actuelle dans la séquence de relance (emails, réseaux, WhatsApp…).</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="18">Date de relance
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Date de la dernière relance effectuée.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="19">Lead à jour de ses infos
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Chaque ligne des prospects/lead doit être complétée intégralement, en particulier tous les URL, afin d'identifier facilement le client. Indiquez 100 % lorsque toutes les informations sont correctement renseignées.</span>
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
                            <span class="tooltip-text">Nombre de messages privés envoyés au prospect sur Instagram.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="22">Follow Insta
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Indique si le compte Instagram du prospect a été suivi.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="23">Com Insta
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nombre de commentaires laissés sur les publications Instagram du prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="24">Formulaire
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Indique si un message a été envoyé via le formulaire du site et son statut.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="25">Fonction du prospect
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Intitulé du poste du lead (nous recherchons le gérant : gérant, gérante, directeur, etc.).</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="26">Email
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Adresse email professionnelle de l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="27">Email gérant
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Adresse email professionnelle du gérant.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="28">Tel Fixe
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Numéro de téléphone fixe de l'entreprise.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="29">Portable
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Numéro de téléphone mobile du prospect.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="30">Site web
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Site internet officiel de l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="31">Pas de site web
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Pas de site internet.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="32">Compte Insta
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nom d'utilisateur du compte Instagram du prospect.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="33">Note
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Note de l'entreprise.</span>
                        </span>
                    </th>
                    
                    <th class="resizable" data-index="34">Avis
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Avis sur l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="35">Nom du scrapping
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Nom de la campagne ou de la source d'import du lead.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="36">URL Maps
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Lien vers la fiche Google Maps de l'entreprise.</span>
                        </span>
                    </th>

                    <th class="resizable" data-index="37">Actions
                        <span class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Modifier, supprimer ou exporter ce lead.</span>
                        </span>
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse($leads as $lead)
                    <tr onclick='openEditModal(@json($lead))' style="cursor:pointer;">
                        <td onclick="event.stopPropagation()">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td>{{ $lead->entreprise ?? '-' }}</td>
                        <td><strong>{{ $lead->prenom_nom ?? '-' }}</strong></td>
                        <td><strong>{{ $lead->nom ?? '-' }}</strong></td>
                        <td>{{ $lead->adresse_postale ?? '-' }}</td>
                        <td>{{ $lead->commentaire ?? '-' }}</td>
                        <td>
                            <span class="badge {{ \Illuminate\Support\Str::slug($lead->chaleur) }}">
                                {{ $lead->chaleur ?? '-' }}
                            </span>
                        </td>
                        <td class="url-cell">
                            @if($lead->url_linkedin)
                                <a href="{{ $lead->url_linkedin }}" target="_blank" onclick="event.stopPropagation()">Voir</a>
                            @else - @endif
                        </td>
                        <td class="url-cell">
                            @if($lead->url_facebook)
                                <a href="{{ $lead->url_facebook }}" target="_blank" onclick="event.stopPropagation()">Voir</a>
                            @else - @endif
                        </td>
                        <td class="url-cell">
                            @if($lead->url_instagramm)
                                <a href="{{ $lead->url_instagramm }}" target="_blank" onclick="event.stopPropagation()">Voir</a>
                            @else - @endif
                        </td>
                        <td><span class="badge">{{ $lead->appel_tel ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->status ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->mp_instagram ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->linkedin_status ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->messenger ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->message_form ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->categorie ?? '-' }}</span></td>
                        <td><span class="badge relance-status">{{ $lead->status_relance ?? '-' }}</span></td>
                        <td>
                            {{ $lead->date_statut ? \Carbon\Carbon::parse($lead->date_statut)->format('d/m/Y') : '-' }}
                        </td>
                        <td><span class="badge pourcent">{{ $lead->enfants_percent ?? '-' }}</span></td>
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
                        <td class="url-cell">
                            @if($lead->url_site)
                                <a href="{{ $lead->url_site }}" target="_blank" onclick="event.stopPropagation()">Voir</a>
                            @else - @endif
                        </td>
                        <td class="checkbox-cell">
                            <input type="checkbox" disabled {{ !$lead->url_site ? 'checked' : '' }}>
                        </td>
                        <td>{{ $lead->compte_insta ?? '-' }}</td>
                        <td><span class="badge">{{ $lead->note ?? '-' }}</span></td>
                        <td><span class="badge">{{ $lead->avis ?? '-' }}</span></td>
                        <td>{{ $lead->nom_global ?? '-' }}</td>
                        <td class="url-cell">
                            @if($lead->url_maps)
                                <a href="{{ $lead->url_maps }}" target="_blank" onclick="event.stopPropagation()">Voir</a>
                            @else - @endif
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
        min-width: 2300px;
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
        white-space: nowrap;
        vertical-align: middle;
        transition: background 0.2s;
    }

    .leads-table tbody tr {
        transition: all 0.2s;
        cursor: pointer;
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
            if (width > 50) { // Largeur minimale
                currentHeader.style.width = width + 'px';
                
                // Appliquer la largeur à toutes les cellules de la colonne
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
                
                // Sauvegarder les largeurs
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
                        
                        // Appliquer aux cellules
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

        // Fonction globale pour réinitialiser les largeurs
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
    | OUVRIR MODAL AJOUT
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

    /*
    |--------------------------------------------------------------------------
    | OUVRIR MODAL EDIT
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | FERMER MODAL
    |--------------------------------------------------------------------------
    */
    function closeModal() {
        leadModal.style.display = "none";
    }

    /*
    |--------------------------------------------------------------------------
    | OUVRIR MODAL SUPPRESSION
    |--------------------------------------------------------------------------
    */
    function openDeleteModal(leadId) {
        deleteForm.action = "/crm/leads/" + leadId;
        deleteModal.style.display = "block";
    }

    /*
    |--------------------------------------------------------------------------
    | FERMER MODAL SUPPRESSION
    |--------------------------------------------------------------------------
    */
    function closeDeleteModal() {
        deleteModal.style.display = "none";
    }

    /*
    |--------------------------------------------------------------------------
    | FERMER EN CLIQUANT A L'EXTERIEUR
    |--------------------------------------------------------------------------
    */
    window.onclick = function(event) {
        if (event.target.classList.contains("modal")) {
            closeModal();
            closeDeleteModal();
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
</script>
@endsection