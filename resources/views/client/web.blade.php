@extends('client.layouts.app')

@section('title', 'Web Scraper')

@section('content')
<div class="card web-scraper">

    {{-- HEADER --}}
    <div class="ws-header">
        <div>
            <h1 class="ws-title">Web Scraper</h1>
            <p class="ws-subtitle">
                Extraction automatique d’emails depuis un site web
            </p>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="ws-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('client.web.scrape') }}" class="ws-form">
    @csrf

    <label class="ws-label">Choisir un site depuis Google Scraper</label>
    <select name="website_select" class="ws-input" style="margin-bottom:15px;">
        <option value="">-- Sélectionner un site --</option>
        @foreach($websites as $site)
            <option value="{{ $site }}">{{ $site }}</option>
        @endforeach
    </select>

    <label class="ws-label">Ou saisir une URL manuellement</label>
    <div class="ws-form-row">
        <input type="url" name="url" placeholder="https://exemple.com" class="ws-input">
        <button type="submit" class="ws-button">
            <i class="fa-solid fa-play"></i> Lancer
        </button>
    </div>

    <p class="ws-hint">
        Les pages contact, mentions légales et à propos sont analysées automatiquement.
    </p>
</form>

<div class="ws-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h1 class="ws-title">Web Scraper</h1>
        <p class="ws-subtitle">
            Extraction automatique d’emails depuis un site web
        </p>
    </div>

    <a href="{{ route('client.web.export.pdf') }}" class="ws-button"
       style="height:42px; text-decoration:none;">
        <i class="fa-solid fa-file-pdf"></i>
        PDF
    </a>
</div>

    {{-- RESULTS --}}
    <h2 class="ws-section-title">Résultats</h2>

    <form method="POST" action="{{ route('client.web.delete.selected') }}">
    @csrf
    @method('DELETE')

    <div style="margin-bottom:15px;">
        <button type="submit" class="ws-button" 
                onclick="return confirm('Supprimer les éléments sélectionnés ?')">
            <i class="fa-solid fa-trash"></i> Supprimer sélection
        </button>
    </div>

    <div class="ws-table-wrapper">
        <table class="ws-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" onclick="toggleAll(this)">
                    </th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Source</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $row)
                <tr>
                    <td>
                        <input type="checkbox" name="selected[]" value="{{ $row->id }}">
                    </td>
                    <td class="ws-strong">{{ $row->name ?? '—' }}</td>
                    <td class="ws-email">{{ $row->email }}</td>
                    <td>
                        <a href="{{ $row->source_url }}" target="_blank" class="ws-link">
                            Ouvrir <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="ws-empty">
                        Aucun résultat pour le moment
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>


    {{-- PAGINATION --}}
    @if ($results->hasPages())
        <div class="ws-pagination">
            {{-- Précédent --}}
            @if ($results->onFirstPage())
                <span class="page disabled">‹</span>
            @else
                <a href="{{ $results->previousPageUrl() }}" class="page">‹</a>
            @endif

            {{-- Pages --}}
            @foreach ($results->getUrlRange(1, $results->lastPage()) as $page => $url)
                @if ($page == $results->currentPage())
                    <span class="page active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Suivant --}}
            @if ($results->hasMorePages())
                <a href="{{ $results->nextPageUrl() }}" class="page">›</a>
            @else
                <span class="page disabled">›</span>
            @endif
        </div>
    @endif

</div>

{{-- STYLE PROFESSIONNEL - NEUTRE - RESPONSIVE --}}
<style>
/*=============================================================================
  WEB SCRAPER - STYLE PROFESSIONNEL NEUTRE
  =============================================================================*/

/*---------------------------------------
  VARIABLES & RESET
---------------------------------------*/
:root {
    --color-primary: #2563eb;
    --color-primary-dark: #1d4ed8;
    --color-primary-light: #3b82f6;
    --color-primary-soft: #dbeafe;
    --color-success: #059669;
    --color-success-light: #d1fae5;
    --color-danger: #dc2626;
    --color-danger-light: #fee2e2;
    --color-gray-50: #f9fafb;
    --color-gray-100: #f3f4f6;
    --color-gray-200: #e5e7eb;
    --color-gray-300: #d1d5db;
    --color-gray-400: #9ca3af;
    --color-gray-500: #6b7280;
    --color-gray-600: #4b5563;
    --color-gray-700: #374151;
    --color-gray-800: #1f2937;
    --color-gray-900: #111827;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --font-sans: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.web-scraper {
    font-family: var(--font-sans);
    max-width: 1440px;
    margin: 0 auto;
}

/*---------------------------------------
  HEADER
---------------------------------------*/
.ws-header {
    margin-bottom: 2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.ws-title {
    margin: 0 0 0.25rem 0;
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--color-gray-800);
    letter-spacing: -0.025em;
    line-height: 1.2;
}

@media (min-width: 640px) {
    .ws-title {
        font-size: 2.25rem;
    }
}

.ws-subtitle {
    margin: 0;
    color: var(--color-gray-500);
    font-size: 1rem;
    font-weight: 400;
}

@media (min-width: 640px) {
    .ws-subtitle {
        font-size: 1.125rem;
    }
}

/*---------------------------------------
  ALERTE SUCCÈS
---------------------------------------*/
.ws-alert-success {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background-color: var(--color-success-light);
    border-left: 4px solid var(--color-success);
    color: #065f46;
    padding: 1rem 1.25rem;
    border-radius: var(--radius-md);
    margin-bottom: 2rem;
    font-weight: 500;
}

.ws-alert-success i {
    font-size: 1.25rem;
}

/*---------------------------------------
  FORMULAIRE
---------------------------------------*/
.ws-form {
    background-color: white;
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-xl);
    padding: 1.75rem 1.5rem;
    margin-bottom: 2.5rem;
    box-shadow: var(--shadow-md);
}

@media (min-width: 640px) {
    .ws-form {
        padding: 2rem;
    }
}

.ws-label {
    font-weight: 600;
    display: block;
    margin-bottom: 0.75rem;
    color: var(--color-gray-700);
    font-size: 0.9375rem;
}

.ws-form-row {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (min-width: 480px) {
    .ws-form-row {
        flex-direction: row;
        align-items: stretch;
    }
}

.ws-input {
    flex: 1;
    padding: 0.875rem 1.25rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-300);
    font-size: 1rem;
    transition: all 0.2s ease;
    background-color: white;
    width: 100%;
}

.ws-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

select.ws-input {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
    background-position: right 1rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.ws-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background-color: var(--color-primary);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    padding: 0.875rem 1.75rem;
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    line-height: 1.5;
    white-space: nowrap;
    width: 100%;
}

@media (min-width: 480px) {
    .ws-button {
        width: auto;
    }
}

.ws-button:hover {
    background-color: var(--color-primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.ws-hint {
    margin-top: 1rem;
    color: var(--color-gray-500);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.ws-hint::before {
    content: "ℹ️";
    font-size: 0.875rem;
}

/*---------------------------------------
  TITRE DE SECTION
---------------------------------------*/
.ws-section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-gray-800);
    margin: 2rem 0 1.25rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--color-gray-200);
}

@media (min-width: 768px) {
    .ws-section-title {
        font-size: 1.75rem;
    }
}

/*---------------------------------------
  TABLEAU
---------------------------------------*/
.ws-table-wrapper {
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-200);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    background-color: white;
    margin-bottom: 1rem;
}

.ws-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9375rem;
    min-width: 650px;
}

.ws-table thead {
    background-color: var(--color-gray-50);
    border-bottom: 2px solid var(--color-gray-200);
}

.ws-table th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-weight: 600;
    color: var(--color-gray-700);
    white-space: nowrap;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.ws-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--color-gray-200);
    vertical-align: middle;
    color: var(--color-gray-700);
}

.ws-table tbody tr:last-child td {
    border-bottom: none;
}

.ws-table tbody tr:hover {
    background-color: var(--color-gray-50);
    transition: background-color 0.15s ease;
}

.ws-strong {
    font-weight: 600;
    color: var(--color-gray-800);
}

.ws-email {
    color: var(--color-primary);
    font-weight: 500;
}

/*---------------------------------------
  CHECKBOXES
---------------------------------------*/
.ws-table input[type="checkbox"] {
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 0.25rem;
    border: 1.5px solid var(--color-gray-400);
    cursor: pointer;
    accent-color: var(--color-primary);
}

/*---------------------------------------
  LIENS
---------------------------------------*/
.ws-link {
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    transition: color 0.2s ease;
}

.ws-link:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

.ws-link i {
    font-size: 0.8125rem;
}

/*---------------------------------------
  ÉTAT VIDE
---------------------------------------*/
.ws-empty {
    padding: 2.5rem 1.5rem;
    text-align: center;
    color: var(--color-gray-500);
    font-style: italic;
    background-color: var(--color-gray-50);
}

/*---------------------------------------
  PAGINATION
---------------------------------------*/
.ws-pagination {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.ws-pagination .page {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.5rem;
    border-radius: var(--radius-md);
    background-color: white;
    border: 1px solid var(--color-gray-300);
    color: var(--color-gray-700);
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.9375rem;
}

.ws-pagination .page:hover:not(.active):not(.disabled) {
    background-color: var(--color-gray-50);
    border-color: var(--color-gray-400);
}

.ws-pagination .active {
    background-color: var(--color-primary);
    border-color: var(--color-primary);
    color: white;
}

.ws-pagination .disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    background-color: var(--color-gray-100);
}

/*---------------------------------------
  RESPONSIVE SPÉCIFIQUE
---------------------------------------*/
@media (max-width: 480px) {
    .ws-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .ws-header .ws-button {
        width: 100%;
        height: auto !important;
        padding: 0.875rem !important;
    }
    
    .ws-form-row .ws-button {
        padding: 0.875rem;
    }
}

/*---------------------------------------
  UTILITAIRES CARD
---------------------------------------*/
.card.web-scraper {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    padding: 1.75rem 1.5rem;
}

@media (min-width: 640px) {
    .card.web-scraper {
        padding: 2rem 2rem;
    }
}

@media (min-width: 1024px) {
    .card.web-scraper {
        padding: 2.5rem 2.5rem;
    }
}

</style>
<script>
function toggleAll(source) {
    checkboxes = document.querySelectorAll('input[name="selected[]"]');
    checkboxes.forEach(cb => cb.checked = source.checked);
}
</script>

@endsection