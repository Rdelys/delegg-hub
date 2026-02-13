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
                            <th>Note</th>
                            <th>Avis</th>
                            <th>Statut Web</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($places as $p)
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $p->id }}" 
                                           class="checkbox row-checkbox">
                                </td>
                                <td class="company-name">{{ $p->name ?? '—' }}</td>
                                <td>{{ $p->category ?? '—' }}</td>
                                <td>{{ $p->address ?? '—' }}</td>
                                <td>{{ $p->phone ?? '—' }}</td>
                                <td>
                                    @if($p->website)
                                        <a href="{{ $p->website }}" 
                                           target="_blank" 
                                           class="website-link">
                                            {{ Str::limit($p->website, 30) }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
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
                                        @if($p->website_scraped)
                                            <span class="status-badge status-success">
                                                <i class="fa-solid fa-check"></i>
                                                Scrappé
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">
                                                <i class="fa-solid fa-clock"></i>
                                                En attente
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
        </div>
    @endif
</div>

<style>
/*=============================================================================
  PROFESSIONAL STYLE - FULL WIDTH - HORIZONTAL SCROLL
  =============================================================================*/

/*---------------------------------------
  CSS VARIABLES
---------------------------------------*/
:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-light: #dbeafe;
    --success: #059669;
    --success-light: #d1fae5;
    --danger: #dc2626;
    --danger-light: #fee2e2;
    --warning: #d97706;
    --warning-light: #fef3c7;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --radius: 0.5rem;
    --radius-lg: 0.75rem;
}

/*---------------------------------------
  RESET & BASE
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
    max-width: 100%;
    min-height: 100vh;
    padding: 1.5rem;
    background: var(--gray-50);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif;
}

@media (min-width: 640px) {
    .google-maps-container {
        padding: 2rem;
    }
}

/*---------------------------------------
  HEADER SECTION
---------------------------------------*/
.page-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (min-width: 768px) {
    .page-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
    margin-bottom: 0.25rem;
    letter-spacing: -0.02em;
}

@media (min-width: 640px) {
    .page-title {
        font-size: 2.25rem;
    }
}

.page-subtitle {
    font-size: 1rem;
    color: var(--gray-500);
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
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    font-size: 0.9375rem;
    font-weight: 500;
    line-height: 1.5;
    border-radius: var(--radius);
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    white-space: nowrap;
}

.btn-pdf {
    background: white;
    border-color: var(--gray-200);
    color: var(--danger);
}

.btn-pdf:hover {
    background: var(--danger-light);
    border-color: var(--danger);
}

.btn-delete {
    background: white;
    border-color: var(--gray-200);
    color: var(--danger);
}

.btn-delete:hover:not(:disabled) {
    background: var(--danger-light);
    border-color: var(--danger);
}

.btn-delete:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: var(--gray-50);
    border-color: var(--gray-200);
    color: var(--gray-400);
}

/*---------------------------------------
  ALERTS
---------------------------------------*/
.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: var(--radius);
    margin-bottom: 1.5rem;
    border-left: 4px solid transparent;
}

.alert-success {
    background: var(--success-light);
    border-left-color: var(--success);
    color: #065f46;
}

.alert i {
    font-size: 1.25rem;
}

/*---------------------------------------
  SEARCH FORM
---------------------------------------*/
.search-form {
    margin-bottom: 2rem;
}

.search-box {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (min-width: 480px) {
    .search-box {
        flex-direction: row;
    }
}

.search-input {
    flex: 1;
    padding: 0.875rem 1.25rem;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    font-size: 1rem;
    transition: all 0.2s;
    background: white;
    width: 100%;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.search-button {
    padding: 0.875rem 2rem;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
}

@media (min-width: 480px) {
    .search-button {
        width: auto;
    }
}

.search-button:hover {
    background: var(--primary-dark);
}

/*---------------------------------------
  LOADER
---------------------------------------*/
.loader {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: var(--radius);
    margin-bottom: 1.5rem;
    border: 1px solid var(--gray-200);
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--gray-200);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 1rem;
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
    gap: 1rem;
    margin: 1.5rem 0 1rem;
}

@media (min-width: 640px) {
    .table-toolbar {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.selection-info {
    padding: 0.5rem 1rem;
    background: var(--gray-100);
    border-radius: 9999px;
    font-size: 0.9375rem;
    color: var(--gray-600);
    display: inline-flex;
    align-items: center;
}

.selection-info span {
    font-weight: 600;
    color: var(--gray-800);
    margin-right: 0.25rem;
}

/*---------------------------------------
  TABLE CONTAINER - HORIZONTAL SCROLL
---------------------------------------*/
.table-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    background: white;
    margin-bottom: 1.5rem;
}

/*---------------------------------------
  DATA TABLE
---------------------------------------*/
.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9375rem;
    min-width: 1000px; /* Forces horizontal scroll on small screens */
}

.data-table thead {
    background: var(--gray-50);
    border-bottom: 2px solid var(--gray-200);
}

.data-table th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.data-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    vertical-align: middle;
}

.data-table tbody tr:hover {
    background: var(--gray-50);
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
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 0.25rem;
    border: 2px solid var(--gray-400);
    cursor: pointer;
    accent-color: var(--primary);
}

/* Company name */
.company-name {
    font-weight: 600;
    color: var(--gray-800);
}

/* Website link */
.website-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.website-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Badges */
.rating-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background: var(--warning-light);
    color: var(--warning);
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
    white-space: nowrap;
}

.reviews-count {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background: var(--gray-100);
    color: var(--gray-600);
    border-radius: 9999px;
    font-size: 0.875rem;
    white-space: nowrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
    white-space: nowrap;
}

.status-success {
    background: var(--success-light);
    color: var(--success);
}

.status-pending {
    background: var(--warning-light);
    color: var(--warning);
}

.text-muted {
    color: var(--gray-400);
}

/*---------------------------------------
  PAGINATION
---------------------------------------*/
.pagination-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.pagination-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.5rem;
    border-radius: var(--radius);
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination-item:hover:not(.active):not(.disabled) {
    background: var(--gray-50);
    border-color: var(--gray-300);
}

.pagination-item.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.pagination-item.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    background: var(--gray-100);
}

/*---------------------------------------
  EMPTY STATE
---------------------------------------*/
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
}

.empty-state i {
    font-size: 3rem;
    color: var(--gray-400);
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
}

/*---------------------------------------
  UTILITIES
---------------------------------------*/
.results-form {
    width: 100%;
}
</style>

<script>
(function() {
    'use strict';

    // Table selection management
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const deleteBtn = document.getElementById('delete-btn');
    const selectedCount = document.getElementById('selected-count');

    function updateSelection() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCount.textContent = checked;
        deleteBtn.disabled = checked === 0;
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelection();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateSelection();
                if (!this.checked && selectAll) {
                    selectAll.checked = false;
                }
            });
        });
    }

    // Form loader
    const searchForm = document.getElementById('searchForm');
    const loader = document.getElementById('loader');

    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            loader.classList.remove('hidden');
        });
    }
})();
</script>
@endsection