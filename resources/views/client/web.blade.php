@extends('client.layouts.app')

@section('title', 'Web Scraper')

@section('content')
<div class="web-scraper-container">
    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Web Scraper</h1>
            <p class="page-subtitle">Extraction automatique d'emails depuis un site web</p>
        </div>
        @if(isset($results) && $results->count())
            <a href="{{ route('client.web.export.pdf') }}" class="btn btn-pdf">
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
    <div class="form-card">
        <form method="POST" action="{{ route('client.web.scrape') }}" class="scraper-form" id="scraperForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Saisir une URL</label>
                <div class="input-group">
                    <input type="url" 
                           name="url" 
                           placeholder="exemple.com ou https://exemple.com" 
                           class="form-input" 
                           value="{{ old('url') }}"
                           required>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-play"></i>
                        <span>Lancer</span>
                    </button>
                </div>
            </div>
            <p class="form-hint">
                <i class="fa-solid fa-circle-info"></i>
                Les pages contact, mentions légales et à propos sont analysées automatiquement.
            </p>
        </form>
    </div>

    {{-- Loader --}}
    <div id="loader" class="loader hidden">
        <div class="spinner"></div>
        <p>Scraping en cours... Analyse du site web</p>
    </div>

    {{-- Results Section --}}
    @if(isset($results) && $results->count())
        <div class="results-header">
            <h2 class="section-title">Résultats</h2>
        </div>

        <form method="POST" action="{{ route('client.web.delete.selected') }}" class="results-form" id="deleteForm">
            @csrf
            @method('DELETE')

            {{-- Toolbar --}}
            <div class="table-toolbar">
                <div class="selection-info">
                    <span id="selected-count">0</span> sélectionné(s)
                </div>
                <button type="submit" 
                        id="delete-btn" 
                        class="btn btn-delete" 
                        onclick="return confirm('Supprimer les éléments sélectionnés ?')"
                        disabled>
                    <i class="fa-solid fa-trash"></i>
                    <span>Supprimer la sélection</span>
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
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Facebook</th>
                            <th>Instagram</th>
                            <th>LinkedIn</th>
                            <th>Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $row)
                        <tr>
                            <td class="checkbox-col">
                                <input type="checkbox" 
                                       name="selected[]" 
                                       value="{{ $row->id }}" 
                                       class="checkbox row-checkbox">
                            </td>
                            <td class="company-name">{{ $row->name ?? '—' }}</td>
                            <td class="email-cell">
                                @if($row->email)
                                    <a href="mailto:{{ $row->email }}" class="email-link">
                                        {{ $row->email }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="social-cell">
                                @if($row->facebook)
                                    <a href="{{ $row->facebook }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="social-link facebook"
                                       title="Facebook">
                                        <i class="fa-brands fa-facebook"></i>
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="social-cell">
                                @if($row->instagram)
                                    <a href="{{ $row->instagram }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="social-link instagram"
                                       title="Instagram">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="social-cell">
                                @if($row->linkedin)
                                    <a href="{{ $row->linkedin }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="social-link linkedin"
                                       title="LinkedIn">
                                        <i class="fa-brands fa-linkedin"></i>
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($row->source_url)
                                    <a href="{{ $row->source_url }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="source-link">
                                        <span>Ouvrir</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty-row">
                                Aucun résultat pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($results->hasPages())
                <div class="pagination-wrapper">
                    @if ($results->onFirstPage())
                        <span class="pagination-item disabled" aria-disabled="true">‹</span>
                    @else
                        <a href="{{ $results->previousPageUrl() }}" class="pagination-item" rel="prev">‹</a>
                    @endif

                    @foreach ($results->getUrlRange(1, $results->lastPage()) as $page => $url)
                        @if ($page == $results->currentPage())
                            <span class="pagination-item active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($results->hasMorePages())
                        <a href="{{ $results->nextPageUrl() }}" class="pagination-item" rel="next">›</a>
                    @else
                        <span class="pagination-item disabled" aria-disabled="true">›</span>
                    @endif
                </div>
            @endif
        </form>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h3 class="empty-state-title">Aucun résultat</h3>
            <p class="empty-state-text">Commencez par saisir une URL pour lancer l'extraction.</p>
        </div>
    @endif
</div>

<style>
/*=============================================================================
  WEB SCRAPER - PROFESSIONAL STYLE - FULL WIDTH - HORIZONTAL SCROLL
  =============================================================================*/

/*---------------------------------------
  CSS VARIABLES
---------------------------------------*/
:root {
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
    
    --success-50: #f0fdf4;
    --success-100: #dcfce7;
    --success-200: #bbf7d0;
    --success-500: #22c55e;
    --success-600: #16a34a;
    --success-700: #15803d;
    
    --danger-50: #fef2f2;
    --danger-100: #fee2e2;
    --danger-200: #fecaca;
    --danger-500: #ef4444;
    --danger-600: #dc2626;
    --danger-700: #b91c1c;
    
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
    
    --facebook: #1877f2;
    --instagram: #e4405f;
    --linkedin: #0a66c2;
    
    --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
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
.web-scraper-container {
    max-width: 1440px;
    margin: 2rem auto;
    padding: 0 1.5rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
}

@media (min-width: 640px) {
    .web-scraper-container {
        padding: 0 2rem;
    }
}

a {
    text-decoration: none;
}

/*---------------------------------------
  HEADER SECTION
---------------------------------------*/
.page-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

@media (min-width: 768px) {
    .page-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
    }
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--gray-900);
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
    margin: 0;
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
    border-radius: var(--radius-lg);
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    white-space: nowrap;
    box-shadow: var(--shadow-xs);
}

.btn-primary {
    background: var(--primary-600);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-700);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: var(--shadow-xs);
}

.btn-pdf {
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--danger-600);
}

.btn-pdf:hover {
    background: var(--danger-50);
    border-color: var(--danger-600);
}

.btn-delete {
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--danger-600);
}

.btn-delete:hover:not(:disabled) {
    background: var(--danger-50);
    border-color: var(--danger-600);
}

.btn-delete:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: var(--gray-50);
    border-color: var(--gray-200);
    color: var(--gray-400);
    box-shadow: none;
}

/*---------------------------------------
  ALERTS
---------------------------------------*/
.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: var(--radius-lg);
    margin-bottom: 1.5rem;
    background: white;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.alert-success {
    border-left: 4px solid var(--success-600);
    color: var(--success-700);
    background: var(--success-50);
}

.alert i {
    font-size: 1.25rem;
}

/*---------------------------------------
  FORM CARD
---------------------------------------*/
.form-card {
    background: white;
    border-radius: var(--radius-xl);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

@media (min-width: 640px) {
    .form-card {
        padding: 2rem;
    }
}

/*---------------------------------------
  FORM ELEMENTS
---------------------------------------*/
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--gray-700);
    font-size: 0.9375rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-lg);
    font-size: 0.9375rem;
    transition: all 0.2s;
    background: white;
    color: var(--gray-900);
}

.form-input::placeholder {
    color: var(--gray-400);
}

.form-input:hover {
    border-color: var(--gray-400);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-600);
    box-shadow: 0 0 0 4px var(--primary-100);
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (min-width: 480px) {
    .input-group {
        flex-direction: row;
    }
    
    .input-group .form-input {
        flex: 1;
    }
}

.form-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    color: var(--gray-500);
    font-size: 0.875rem;
    padding: 0.75rem;
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
}

/*---------------------------------------
  LOADER
---------------------------------------*/
.loader {
    text-align: center;
    padding: 2.5rem;
    background: white;
    border-radius: var(--radius-xl);
    margin-bottom: 1.5rem;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.spinner {
    width: 44px;
    height: 44px;
    border: 3px solid var(--gray-200);
    border-top-color: var(--primary-600);
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
  RESULTS HEADER
---------------------------------------*/
.results-header {
    margin: 2rem 0 1.5rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
    letter-spacing: -0.01em;
}

/*---------------------------------------
  TABLE TOOLBAR
---------------------------------------*/
.table-toolbar {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin: 1rem 0;
    padding: 1rem;
    background: white;
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

@media (min-width: 640px) {
    .table-toolbar {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1.5rem;
    }
}

.selection-info {
    padding: 0.375rem 1rem;
    background: var(--gray-100);
    border-radius: 9999px;
    font-size: 0.875rem;
    color: var(--gray-600);
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--gray-200);
}

.selection-info span {
    font-weight: 700;
    color: var(--gray-900);
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
    box-shadow: var(--shadow-sm);
}

/*---------------------------------------
  DATA TABLE
---------------------------------------*/
.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9375rem;
    min-width: 800px;
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
    transition: background-color 0.2s;
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
    border-radius: var(--radius-sm);
    border: 2px solid var(--gray-400);
    cursor: pointer;
    accent-color: var(--primary-600);
    transition: all 0.2s;
}

.checkbox:hover {
    border-color: var(--primary-600);
}

/* Company name */
.company-name {
    font-weight: 600;
    color: var(--gray-900);
}

/* Email cell */
.email-cell {
    max-width: 200px;
}

.email-link {
    color: var(--primary-600);
    text-decoration: none;
    font-weight: 500;
    word-break: break-all;
    transition: color 0.2s;
}

.email-link:hover {
    color: var(--primary-700);
    text-decoration: underline;
}

/* Social cells */
.social-cell {
    text-align: center;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 9999px;
    transition: all 0.2s;
    font-size: 1.25rem;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
}

.social-link.facebook {
    color: var(--facebook);
}

.social-link.instagram {
    color: var(--instagram);
}

.social-link.linkedin {
    color: var(--linkedin);
}

.social-link:hover {
    transform: scale(1.1);
    background: white;
    box-shadow: var(--shadow-md);
}

/* Source link */
.source-link {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    color: var(--gray-600);
    text-decoration: none;
    font-size: 0.875rem;
    padding: 0.375rem 0.875rem;
    border-radius: 9999px;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    transition: all 0.2s;
}

.source-link:hover {
    background: var(--gray-100);
    color: var(--gray-900);
    border-color: var(--gray-300);
}

.source-link i {
    font-size: 0.75rem;
}

/* Text utilities */
.text-muted {
    color: var(--gray-400);
}

/* Empty row */
.empty-row {
    padding: 3rem !important;
    text-align: center;
    color: var(--gray-500);
    font-style: italic;
    background: var(--gray-50);
}

/*---------------------------------------
  EMPTY STATE
---------------------------------------*/
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--radius-xl);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.empty-state-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1.5rem;
    background: var(--gray-50);
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--gray-200);
}

.empty-state-icon i {
    font-size: 2rem;
    color: var(--gray-400);
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: var(--gray-500);
    font-size: 0.9375rem;
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
    border-radius: var(--radius-lg);
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 0.9375rem;
    box-shadow: var(--shadow-xs);
}

.pagination-item:hover:not(.active):not(.disabled) {
    background: var(--gray-50);
    border-color: var(--gray-300);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.pagination-item.active {
    background: var(--primary-600);
    border-color: var(--primary-600);
    color: white;
    box-shadow: var(--shadow-sm);
}

.pagination-item.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    background: var(--gray-100);
}

/*---------------------------------------
  RESPONSIVE ADJUSTMENTS
---------------------------------------*/
@media (max-width: 480px) {
    .page-header .btn {
        width: 100%;
    }
    
    .input-group .btn {
        width: 100%;
    }
    
    .social-link {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1.25rem;
    }
    
    .table-toolbar .btn {
        width: 100%;
    }
}
</style>

<script>
(function() {
    'use strict';

    // Initialize all functionality when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTableSelection();
        initializeFormLoader();
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
            
            // Update select all state
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

        // Initial update
        updateSelection();
    }

    // Form loader
    function initializeFormLoader() {
        const scraperForm = document.getElementById('scraperForm');
        const loader = document.getElementById('loader');

        if (scraperForm && loader) {
            scraperForm.addEventListener('submit', function(e) {
                // Basic URL validation
                const urlInput = this.querySelector('input[name="url"]');
                if (urlInput && urlInput.value.trim() === '') {
                    e.preventDefault();
                    alert('Veuillez saisir une URL');
                    return;
                }
                
                loader.classList.remove('hidden');
            });
        }
    }
})();
</script>
@endsection