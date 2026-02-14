@extends('client.layouts.app')

@section('title', 'Google Maps')

@section('content')
<div class="google-maps-container">
    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Google Maps</h1>
            <p class="page-subtitle">Recherche intelligente d'entreprises locales</p>
        </div>
        
        @if(isset($places) && $places->count())
            <a href="{{ route('client.google.export.pdf') }}" class="btn btn-pdf">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Export PDF</span>
            </a>

            <a href="{{ route('client.google.export.excel') }}" class="btn btn-excel">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export Excel</span>
            </a>

            {{-- Dans la section page-header, après les boutons d'export --}}
@if(isset($places) && $places->count())
    <div class="btn-group">
        <a href="{{ route('client.google.export.pdf') }}" class="btn btn-pdf">
            <i class="fa-solid fa-file-pdf"></i>
            <span>PDF</span>
        </a>

        <a href="{{ route('client.google.export.excel') }}" class="btn btn-excel">
            <i class="fa-solid fa-file-excel"></i>
            <span>Excel</span>
        </a>

        {{-- Nouveaux boutons pour le scraping --}}
        <button type="button" class="btn btn-scraping" id="retryScrapingBtn">
            <i class="fa-solid fa-rotate"></i>
            <span>Relancer scraping</span>
        </button>

        <button type="button" class="btn btn-stats" id="scrapingStatsBtn">
            <i class="fa-solid fa-chart-simple"></i>
            <span>Statistiques</span>
        </button>
    </div>
@endif
        @endif
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Search Form --}}
    <form method="POST" action="{{ route('client.google.scrape') }}" class="search-form" id="searchForm">
        @csrf
        <div class="search-box">
            <input type="text" 
                   name="query" 
                   required 
                   placeholder="Ex : plombier Paris" 
                   value="{{ old('query') }}"
                   class="search-input">
            <button type="submit" class="search-button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>

    {{-- Loader --}}
    <div id="loader" class="loader hidden">
        <div class="spinner"></div>
        <p>Scraping en cours... Analyse Google + Sites web</p>
    </div>

    {{-- Results Section --}}
    @if(isset($places) && $places->count())
        <form method="POST" 
              action="{{ route('client.google.delete.selected') }}" 
              onsubmit="return confirm('Supprimer les lignes sélectionnées ?')"
              class="results-form">
            @csrf
            @method('DELETE')

            {{-- Toolbar --}}
            <div class="table-toolbar">
                <div class="selection-info">
                    <span id="selected-count">0</span> sélectionné(s)
                </div>
                <button type="submit" id="delete-btn" class="btn btn-delete" disabled>
                    <i class="fa-solid fa-trash"></i>
                    <span>Supprimer</span>
                </button>
            </div>

            {{-- Table Container with Horizontal Scroll --}}
            <div class="table-container">
                <table class="data-table">
                    {{-- Dans le tableau, ajoutez les colonnes email et réseaux sociaux --}}
<thead>
    <tr>
        <th class="checkbox-col">
            <input type="checkbox" id="select-all" class="checkbox">
        </th>
        <th>Entreprise</th>
        <th>Catégorie</th>
        <th>Adresse</th>
        <th>Téléphone</th>
        <th>Site web</th>
        <th>Email</th>
        <th>Réseaux</th>
        <th>Note</th>
        <th>Avis</th>
        <th>Statut</th>
    </tr>
</thead>
<tbody>
    @foreach($places as $p)
        <tr>
            <td class="checkbox-col">
                <input type="checkbox" name="selected[]" value="{{ $p->id }}" class="checkbox row-checkbox">
            </td>
            <td class="company-name">{{ $p->name ?? '—' }}</td>
            <td>{{ $p->category ?? '—' }}</td>
            <td>{{ $p->address ?? '—' }}</td>
            <td>{{ $p->phone ?? '—' }}</td>
            <td>
                @if($p->website)
                    <a href="{{ $p->website }}" target="_blank" class="website-link">
                        {{ Str::limit($p->website, 30) }}
                    </a>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                @if($p->email)
                    <span class="email-badge" title="{{ $p->email }}">
                        {{ Str::limit($p->email, 20) }}
                    </span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                <div class="social-links">
                    @if($p->facebook)
                        <a href="{{ $p->facebook }}" target="_blank" class="social-link facebook">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                    @endif
                    @if($p->instagram)
                        <a href="{{ $p->instagram }}" target="_blank" class="social-link instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    @endif
                    @if($p->linkedin)
                        <a href="{{ $p->linkedin }}" target="_blank" class="social-link linkedin">
                            <i class="fa-brands fa-linkedin"></i>
                        </a>
                    @endif
                    @if(!$p->facebook && !$p->instagram && !$p->linkedin)
                        <span class="text-muted">—</span>
                    @endif
                </div>
            </td>
            <td>
                @if($p->rating)
                    <span class="rating-badge">
                        ⭐ {{ number_format($p->rating, 1) }}
                    </span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                @if($p->reviews_count)
                    <span class="reviews-count">
                        {{ $p->reviews_count }} avis
                    </span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                @if($p->website)
                    @if($p->contact_scraped_at)
                        <span class="status-badge status-success">
                            <i class="fa-solid fa-check"></i>
                            Contacts OK
                        </span>
                    @elseif($p->website_scraped)
                        <span class="status-badge status-warning">
                            <i class="fa-solid fa-clock"></i>
                            En attente
                        </span>
                    @else
                        <span class="status-badge status-pending">
                            <i class="fa-solid fa-hourglass"></i>
                            Planifié
                        </span>
                    @endif
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($places->hasPages())
                <div class="pagination-wrapper">
                    @if ($places->onFirstPage())
                        <span class="pagination-item disabled">‹</span>
                    @else
                        <a href="{{ $places->previousPageUrl() }}" class="pagination-item">‹</a>
                    @endif

                    @foreach ($places->getUrlRange(
                        max(1, $places->currentPage() - 2),
                        min($places->lastPage(), $places->currentPage() + 2)
                    ) as $page => $url)
                        @if ($page == $places->currentPage())
                            <span class="pagination-item active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($places->hasMorePages())
                        <a href="{{ $places->nextPageUrl() }}" class="pagination-item">›</a>
                    @else
                        <span class="pagination-item disabled">›</span>
                    @endif
                </div>
            @endif
        </form>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-magnifying-glass"></i>
            <h3>Aucun résultat</h3>
            <p>Commencez par effectuer une recherche</p>
        </div>
    @endif
</div>

<style>
/*=============================================================================
  GOOGLE MAPS - PROFESSIONAL PREMIUM STYLE
  =============================================================================*/

/*---------------------------------------
  DESIGN SYSTEM VARIABLES
---------------------------------------*/
:root {
    /* Primary Colors */
    --primary-50: #eff6ff;
    --primary-100: #dbeafe;
    --primary-200: #bfdbfe;
    --primary-300: #93c5fd;
    --primary-400: #60a5fa;
    --primary-500: #3b82f6;
    --primary-600: #2563eb;
    --primary-700: #1d4ed8;
    --primary-800: #1e40af;
    --primary-900: #1e3a8a;
    
    /* Success Colors */
    --success-50: #f0fdf4;
    --success-100: #dcfce7;
    --success-200: #bbf7d0;
    --success-300: #86efac;
    --success-400: #4ade80;
    --success-500: #22c55e;
    --success-600: #16a34a;
    --success-700: #15803d;
    --success-800: #166534;
    --success-900: #14532d;
    
    /* Danger Colors */
    --danger-50: #fef2f2;
    --danger-100: #fee2e2;
    --danger-200: #fecaca;
    --danger-300: #fca5a5;
    --danger-400: #f87171;
    --danger-500: #ef4444;
    --danger-600: #dc2626;
    --danger-700: #b91c1c;
    --danger-800: #991b1b;
    --danger-900: #7f1d1d;
    
    /* Warning Colors */
    --warning-50: #fffbeb;
    --warning-100: #fef3c7;
    --warning-200: #fde68a;
    --warning-300: #fcd34d;
    --warning-400: #fbbf24;
    --warning-500: #f59e0b;
    --warning-600: #d97706;
    --warning-700: #b45309;
    --warning-800: #92400e;
    --warning-900: #78350f;
    
    /* Gray Scale */
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    
    /* Shadows */
    --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    
    /* Border Radius */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    
    /* Transitions */
    --transition-base: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Spacing */
    --spacing-1: 0.25rem;
    --spacing-2: 0.5rem;
    --spacing-3: 0.75rem;
    --spacing-4: 1rem;
    --spacing-5: 1.25rem;
    --spacing-6: 1.5rem;
    --spacing-8: 2rem;
    --spacing-10: 2.5rem;
    --spacing-12: 3rem;
}

/*---------------------------------------
  BASE STYLES
---------------------------------------*/
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/*---------------------------------------
  MAIN CONTAINER
---------------------------------------*/
.google-maps-container {
    max-width: 1440px;
    margin: 0 auto;
    padding: var(--spacing-6);
    min-height: 100vh;
    background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif;
}

@media (min-width: 640px) {
    .google-maps-container {
        padding: var(--spacing-8);
    }
}

@media (min-width: 1024px) {
    .google-maps-container {
        padding: var(--spacing-10);
    }
}

/*---------------------------------------
  HEADER SECTION
---------------------------------------*/
.page-header {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
    margin-bottom: var(--spacing-8);
    background: white;
    padding: var(--spacing-6);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.9);
}

@media (min-width: 768px) {
    .page-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: var(--spacing-6) var(--spacing-8);
    }
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-700) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
    margin-bottom: var(--spacing-2);
    letter-spacing: -0.03em;
}

@media (min-width: 640px) {
    .page-title {
        font-size: 2.5rem;
    }
}

.page-subtitle {
    font-size: 1rem;
    color: var(--gray-500);
    margin: 0;
    font-weight: 400;
}

@media (min-width: 640px) {
    .page-subtitle {
        font-size: 1.125rem;
    }
}

/*---------------------------------------
  BUTTONS
---------------------------------------*/
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    padding: var(--spacing-3) var(--spacing-5);
    font-size: 0.9375rem;
    font-weight: 600;
    line-height: 1.5;
    border-radius: var(--radius-lg);
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--transition-base);
    text-decoration: none;
    white-space: nowrap;
    box-shadow: var(--shadow-xs);
    position: relative;
    overflow: hidden;
}

.btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn:active::after {
    width: 300px;
    height: 300px;
}

.btn-pdf {
    background: white;
    border-color: var(--gray-200);
    color: var(--danger-600);
}

.btn-pdf:hover {
    background: var(--danger-50);
    border-color: var(--danger-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-excel {
    background: white;
    border-color: var(--gray-200);
    color: var(--success-600);
}

.btn-excel:hover {
    background: var(--success-50);
    border-color: var(--success-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-delete {
    background: white;
    border-color: var(--gray-200);
    color: var(--danger-600);
}

.btn-delete:hover:not(:disabled) {
    background: var(--danger-50);
    border-color: var(--danger-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-delete:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    background: var(--gray-50);
    border-color: var(--gray-200);
    color: var(--gray-400);
    box-shadow: none;
    transform: none;
}

/*---------------------------------------
  ALERTS
---------------------------------------*/
.alert {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    padding: var(--spacing-4) var(--spacing-5);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-6);
    background: white;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    border-left: 4px solid var(--success-600);
    background: linear-gradient(to right, var(--success-50), white);
    color: var(--success-700);
}

.alert i {
    font-size: 1.25rem;
}

/*---------------------------------------
  SEARCH FORM
---------------------------------------*/
.search-form {
    margin-bottom: var(--spacing-8);
}

.search-box {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);
    background: white;
    padding: var(--spacing-2);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
}

@media (min-width: 480px) {
    .search-box {
        flex-direction: row;
        padding: var(--spacing-1);
    }
}

.search-input {
    flex: 1;
    padding: var(--spacing-4) var(--spacing-5);
    border: none;
    border-radius: var(--radius-xl);
    font-size: 1rem;
    transition: var(--transition-base);
    background: transparent;
    color: var(--gray-900);
}

.search-input::placeholder {
    color: var(--gray-400);
    font-weight: 400;
}

.search-input:focus {
    outline: none;
    box-shadow: inset 0 0 0 2px var(--primary-200);
}

.search-button {
    padding: var(--spacing-4) var(--spacing-6);
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    color: white;
    border: none;
    border-radius: var(--radius-xl);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-base);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    width: 100%;
    position: relative;
    overflow: hidden;
}

@media (min-width: 480px) {
    .search-button {
        width: auto;
        min-width: 120px;
    }
}

.search-button:hover {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-800));
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.search-button:active {
    transform: translateY(0);
}

/*---------------------------------------
  LOADER
---------------------------------------*/
.loader {
    text-align: center;
    padding: var(--spacing-8);
    background: white;
    border-radius: var(--radius-2xl);
    margin-bottom: var(--spacing-6);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid var(--gray-200);
    border-top-color: var(--primary-600);
    border-right-color: var(--primary-400);
    border-bottom-color: var(--primary-300);
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
    margin: 0 auto var(--spacing-4);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.hidden {
    display: none;
}

/*---------------------------------------
  TABLE TOOLBAR
---------------------------------------*/
.table-toolbar {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);
    margin: var(--spacing-6) 0 var(--spacing-4);
    padding: var(--spacing-3) var(--spacing-4);
    background: white;
    border-radius: var(--radius-xl);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

@media (min-width: 640px) {
    .table-toolbar {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: var(--spacing-3) var(--spacing-5);
    }
}

.selection-info {
    padding: var(--spacing-2) var(--spacing-4);
    background: var(--gray-100);
    border-radius: 9999px;
    font-size: 0.9375rem;
    color: var(--gray-600);
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--gray-200);
}

.selection-info span {
    font-weight: 700;
    color: var(--gray-900);
    margin-right: var(--spacing-1);
}

/*---------------------------------------
  TABLE CONTAINER
---------------------------------------*/
.table-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    background: white;
    margin-bottom: var(--spacing-6);
    box-shadow: var(--shadow-lg);
    transition: var(--transition-base);
}

.table-container:hover {
    box-shadow: var(--shadow-xl);
}

/*---------------------------------------
  DATA TABLE
---------------------------------------*/
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.9375rem;
    min-width: 1000px;
}

.data-table thead {
    background: linear-gradient(to bottom, var(--gray-50), white);
    border-bottom: 2px solid var(--gray-200);
}

.data-table th {
    padding: var(--spacing-4) var(--spacing-5);
    text-align: left;
    font-weight: 700;
    font-size: 0.875rem;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
    position: sticky;
    top: 0;
    background: inherit;
    z-index: 10;
}

.data-table td {
    padding: var(--spacing-4) var(--spacing-5);
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    vertical-align: middle;
    transition: background-color 0.2s;
}

.data-table tbody tr {
    transition: var(--transition-base);
}

.data-table tbody tr:hover {
    background: var(--gray-50);
    transform: scale(1);
    box-shadow: var(--shadow-sm);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

/* Checkbox column */
.checkbox-col {
    width: 50px;
    text-align: center;
}

.checkbox {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: var(--radius-sm);
    border: 2px solid var(--gray-400);
    cursor: pointer;
    accent-color: var(--primary-600);
    transition: var(--transition-base);
}

.checkbox:hover {
    border-color: var(--primary-600);
    transform: scale(1.1);
}

.checkbox:checked {
    background-color: var(--primary-600);
    border-color: var(--primary-600);
}

/* Company name */
.company-name {
    font-weight: 700;
    color: var(--gray-900);
}

/* Website link */
.website-link {
    color: var(--primary-600);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-base);
    display: inline-block;
    position: relative;
}

.website-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--primary-600);
    transition: width 0.2s ease;
}

.website-link:hover {
    color: var(--primary-700);
}

.website-link:hover::after {
    width: 100%;
}

/* Badges */
.rating-badge {
    display: inline-flex;
    align-items: center;
    padding: var(--spacing-2) var(--spacing-3);
    background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
    color: var(--warning-700);
    border-radius: 9999px;
    font-weight: 700;
    font-size: 0.875rem;
    white-space: nowrap;
    border: 1px solid var(--warning-200);
    box-shadow: var(--shadow-xs);
}

.reviews-count {
    display: inline-flex;
    align-items: center;
    padding: var(--spacing-2) var(--spacing-3);
    background: var(--gray-100);
    color: var(--gray-700);
    border-radius: 9999px;
    font-size: 0.875rem;
    white-space: nowrap;
    border: 1px solid var(--gray-200);
    font-weight: 600;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-3);
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
    white-space: nowrap;
    border: 1px solid transparent;
    box-shadow: var(--shadow-xs);
}

.status-success {
    background: linear-gradient(135deg, var(--success-50), var(--success-100));
    color: var(--success-700);
    border-color: var(--success-200);
}

.status-pending {
    background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
    color: var(--warning-700);
    border-color: var(--warning-200);
}

.text-muted {
    color: var(--gray-400);
    font-style: italic;
}

/*---------------------------------------
  PAGINATION
---------------------------------------*/
.pagination-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    margin-top: var(--spacing-8);
}

.pagination-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.75rem;
    height: 2.75rem;
    padding: 0 var(--spacing-3);
    border-radius: var(--radius-lg);
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-base);
    font-size: 0.9375rem;
    box-shadow: var(--shadow-xs);
}

.pagination-item:hover:not(.active):not(.disabled) {
    background: var(--gray-50);
    border-color: var(--gray-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.pagination-item.active {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    border-color: var(--primary-600);
    color: white;
    box-shadow: var(--shadow-md);
}

.pagination-item.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
    background: var(--gray-100);
}

/*---------------------------------------
  EMPTY STATE
---------------------------------------*/
.empty-state {
    text-align: center;
    padding: var(--spacing-12) var(--spacing-6);
    background: white;
    border-radius: var(--radius-2xl);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.empty-state i {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: var(--spacing-4);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: var(--spacing-2);
}

.empty-state p {
    color: var(--gray-500);
    font-size: 1rem;
}

/*---------------------------------------
  RESPONSIVE ADJUSTMENTS
---------------------------------------*/
@media (max-width: 640px) {
    .page-header {
        padding: var(--spacing-4);
    }
    
    .btn {
        width: 100%;
        padding: var(--spacing-3) var(--spacing-4);
    }
    
    .table-toolbar .btn {
        width: 100%;
    }
    
    .checkbox {
        width: 1.5rem;
        height: 1.5rem;
    }
    
    .data-table th,
    .data-table td {
        padding: var(--spacing-3) var(--spacing-4);
    }
    
    .rating-badge,
    .reviews-count,
    .status-badge {
        padding: var(--spacing-1) var(--spacing-2);
        font-size: 0.8125rem;
    }
}

/*---------------------------------------
  SCROLLBAR STYLING
---------------------------------------*/
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: var(--radius-lg);
}

::-webkit-scrollbar-thumb {
    background: var(--gray-400);
    border-radius: var(--radius-lg);
    transition: var(--transition-base);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--gray-500);
}

/*---------------------------------------
  FOCUS STATES
---------------------------------------*/
:focus-visible {
    outline: 2px solid var(--primary-500);
    outline-offset: 2px;
}

/*---------------------------------------
  ANIMATIONS
---------------------------------------*/
@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.loading {
    animation: shimmer 2s infinite;
    background: linear-gradient(to right, var(--gray-100) 4%, var(--gray-200) 25%, var(--gray-100) 36%);
    background-size: 1000px 100%;
}
</style>

<script>
(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTableSelection();
        initializeFormLoader();
        initializeButtonEffects();
    });

    // Table selection management
    function initializeTableSelection() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const deleteBtn = document.getElementById('delete-btn');
        const selectedCount = document.getElementById('selected-count');

        if (!selectAll || !checkboxes.length || !deleteBtn || !selectedCount) return;

        function updateSelection() {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            selectedCount.textContent = checked;
            deleteBtn.disabled = checked === 0;
            
            if (selectAll) {
                selectAll.checked = checked === checkboxes.length && checkboxes.length > 0;
                selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
            }
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelection();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelection);
        });

        updateSelection();
    }

    // Form loader
    function initializeFormLoader() {
        const searchForm = document.getElementById('searchForm');
        const loader = document.getElementById('loader');

        if (searchForm && loader) {
            searchForm.addEventListener('submit', function(e) {
                const queryInput = this.querySelector('input[name="query"]');
                if (queryInput && queryInput.value.trim() === '') {
                    e.preventDefault();
                    showNotification('Veuillez saisir un terme de recherche', 'error');
                    return;
                }
                
                loader.classList.remove('hidden');
            });
        }
    }

    // Button ripple effects
    function initializeButtonEffects() {
        const buttons = document.querySelectorAll('.btn');
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                
                const x = e.clientX - e.target.offsetLeft;
                const y = e.clientY - e.target.offsetTop;
                
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
})();

// Dans la section script, après initializeButtonEffects()

// Retry scraping
const retryBtn = document.getElementById('retryScrapingBtn');
if (retryBtn) {
    retryBtn.addEventListener('click', function() {
        showConfirmation(
            'Relancer le scraping',
            'Voulez-vous relancer le scraping pour tous les sites web non traités ?',
            function() {
                retryBtn.disabled = true;
                retryBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Scraping en cours...';
                
                fetch('{{ route("client.google.retry-scraping") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                })
                .catch(error => {
                    showNotification('Erreur lors du lancement du scraping', 'error');
                    retryBtn.disabled = false;
                    retryBtn.innerHTML = '<i class="fa-solid fa-rotate"></i> Relancer scraping';
                });
            }
        );
    });
}

// Scraping stats
const statsBtn = document.getElementById('scrapingStatsBtn');
if (statsBtn) {
    statsBtn.addEventListener('click', function() {
        fetch('{{ route("client.google.scraping-stats") }}')
        .then(response => response.json())
        .then(stats => {
            showStatsModal(stats);
        });
    });
}

// Fonction de confirmation
function showConfirmation(title, message, onConfirm) {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content">
            <h3>${title}</h3>
            <p>${message}</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="cancelBtn">Annuler</button>
                <button class="btn btn-primary" id="confirmBtn">Confirmer</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    document.getElementById('cancelBtn').addEventListener('click', () => {
        modal.remove();
    });
    
    document.getElementById('confirmBtn').addEventListener('click', () => {
        modal.remove();
        onConfirm();
    });
}

// Fonction pour afficher les stats
function showStatsModal(stats) {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content stats-modal">
            <h3><i class="fa-solid fa-chart-simple"></i> Statistiques de scraping</h3>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">${stats.total}</div>
                    <div class="stat-label">Total entreprises</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value">${stats.with_website}</div>
                    <div class="stat-label">Avec site web</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value">${stats.scraped}</div>
                    <div class="stat-label">Sites scrappés</div>
                </div>
                
                <div class="stat-card highlight">
                    <div class="stat-value">${stats.pending}</div>
                    <div class="stat-label">En attente</div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-value">${stats.with_email}</div>
                    <div class="stat-label">Emails trouvés</div>
                </div>
            </div>
            
            <div class="progress-section">
                <div class="progress-label">
                    <span>Progression</span>
                    <span>${Math.round((stats.scraped / stats.with_website) * 100 || 0)}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: ${(stats.scraped / stats.with_website) * 100 || 0}%"></div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-primary" id="closeStatsBtn">Fermer</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    document.getElementById('closeStatsBtn').addEventListener('click', () => {
        modal.remove();
    });
}
</script>

<style>
/* Additional animations for buttons */
.btn .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Notification styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    border-left: 4px solid var(--primary-600);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transform: translateX(120%);
    transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 9999;
    min-width: 300px;
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    border-left-color: var(--success-600);
    background: linear-gradient(to right, var(--success-50), white);
}

.notification-error {
    border-left-color: var(--danger-600);
    background: linear-gradient(to right, var(--danger-50), white);
}

.notification i {
    font-size: 1.25rem;
}

.notification-success i {
    color: var(--success-600);
}

.notification-error i {
    color: var(--danger-600);
}

/* Badges email */
.email-badge {
    display: inline-flex;
    align-items: center;
    padding: var(--spacing-1) var(--spacing-3);
    background: linear-gradient(135deg, var(--primary-50), var(--primary-100));
    color: var(--primary-700);
    border-radius: 9999px;
    font-size: 0.875rem;
    border: 1px solid var(--primary-200);
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Social links */
.social-links {
    display: flex;
    gap: var(--spacing-2);
    flex-wrap: wrap;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
    color: white;
    transition: var(--transition-base);
    text-decoration: none;
}

.social-link i {
    font-size: 1rem;
}

.social-link.facebook {
    background: #1877f2;
}

.social-link.instagram {
    background: linear-gradient(45deg, #f09433, #d62976, #962fbf, #4f5bd5);
}

.social-link.linkedin {
    background: #0077b5;
}

.social-link:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.status-badge.status-warning {
    background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
    color: var(--warning-700);
    border-color: var(--warning-200);
}

/* Dans le style, après btn-excel */
.btn-group {
    display: flex;
    gap: var(--spacing-2);
    flex-wrap: wrap;
}

.btn-scraping {
    background: white;
    border-color: var(--gray-200);
    color: var(--primary-600);
}

.btn-scraping:hover {
    background: var(--primary-50);
    border-color: var(--primary-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-stats {
    background: white;
    border-color: var(--gray-200);
    color: var(--gray-700);
}

.btn-stats:hover {
    background: var(--gray-100);
    border-color: var(--gray-500);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
</style>
@endsection