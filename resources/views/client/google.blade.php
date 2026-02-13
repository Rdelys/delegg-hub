@extends('client.layouts.app')

@section('title', 'Google Maps')

@section('content')

<div class="google-wrapper">

    <div class="google-card">

        {{-- HEADER --}}
        <div class="gs-header">
            <div>
                <h1 class="gs-title">Google Maps</h1>
                <p class="gs-subtitle">
                    Recherche intelligente d'entreprises locales
                </p>
            </div>

            @if(isset($places) && $places->count())
                <a href="{{ route('client.google.export.pdf') }}" class="gs-btn gs-btn-pdf">
                    <i class="fa-solid fa-file-pdf"></i>
                    Export PDF
                </a>
            @endif
        </div>

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="gs-alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('client.google.scrape') }}" class="gs-form">
            @csrf
            <div class="gs-search-box">
                <input type="text"
                       name="query"
                       required
                       placeholder="Ex : plombier Paris"
                       value="{{ old('query') }}">
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
        <div id="loader" class="gs-loader hidden">
    <div class="spinner"></div>
    <p>Scraping en cours... Analyse Google + Sites web</p>
</div>


        {{-- RESULTS --}}
        @if(isset($places) && $places->count())

            <form method="POST"
                  action="{{ route('client.google.delete.selected') }}"
                  onsubmit="return confirm('Supprimer les lignes sélectionnées ?')">
                @csrf
                @method('DELETE')

                <div class="gs-toolbar">
                    <div class="gs-count">
                        <span id="selected-count">0</span> sélectionné(s)
                    </div>

                    <button type="submit"
                            id="delete-btn"
                            class="gs-btn gs-btn-danger"
                            disabled>
                        <i class="fa-solid fa-trash"></i>
                        Supprimer
                    </button>
                </div>

                <div class="gs-table-wrapper">
                    <table class="gs-table">
                        <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Entreprise</th>
                            <th>Catégorie</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th>Site web</th>
                            <th>Statut Web</th>

                        </tr>
                        </thead>

                        <tbody>
                        @foreach($places as $p)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           name="selected[]"
                                           value="{{ $p->id }}"
                                           class="row-checkbox">
                                </td>
                                <td class="gs-strong">{{ $p->name ?? '—' }}</td>
                                <td>{{ $p->category ?? '—' }}</td>
                                <td>{{ $p->address ?? '—' }}</td>
                                <td>{{ $p->phone ?? '—' }}</td>
                                <td>
                                    @if($p->website)
                                        <a href="{{ $p->website }}"
                                           target="_blank"
                                           class="gs-link">
                                            {{ $p->website }}
                                        </a>
                                    @else
                                        <span class="gs-muted">—</span>
                                    @endif
                                </td>
                                <td>
@if($p->website)
    @if($p->website_scraped)
        <span class="gs-badge-success">
            <i class="fa-solid fa-check"></i> Scrappé
        </span>
    @else
        <span class="gs-badge-pending">
            <i class="fa-solid fa-clock"></i> En attente
        </span>
    @endif
@else
    <span class="gs-muted">—</span>
@endif
</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION PREMIUM --}}
                @if ($places->hasPages())
                    <div class="gs-pagination">

                        @if ($places->onFirstPage())
                            <span class="pg disabled">‹</span>
                        @else
                            <a href="{{ $places->previousPageUrl() }}" class="pg">‹</a>
                        @endif

                        @foreach ($places->getUrlRange(
                            max(1, $places->currentPage() - 2),
                            min($places->lastPage(), $places->currentPage() + 2)
                        ) as $page => $url)

                            @if ($page == $places->currentPage())
                                <span class="pg active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pg">{{ $page }}</a>
                            @endif

                        @endforeach

                        @if ($places->hasMorePages())
                            <a href="{{ $places->nextPageUrl() }}" class="pg">›</a>
                        @else
                            <span class="pg disabled">›</span>
                        @endif

                    </div>
                @endif

            </form>

        @else
            <div class="gs-empty">
                <i class="fa-solid fa-magnifying-glass"></i>
                <h3>Aucun résultat</h3>
            </div>
        @endif

    </div>
</div>


{{-- SCRIPT --}}
<script>
const selectAll = document.getElementById('select-all');
const checkboxes = document.querySelectorAll('.row-checkbox');
const deleteBtn = document.getElementById('delete-btn');
const selectedCount = document.getElementById('selected-count');

function updateSelection() {
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    selectedCount.textContent = checked;
    deleteBtn.disabled = checked === 0;
}

if(selectAll){
    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelection();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateSelection();
            if (!this.checked) selectAll.checked = false;
        });
    });
}
</script>


<style>
/*=============================================================================
  STYLE PROFESSIONNEL - NEUTRE - RESPONSIVE
  =============================================================================*/

/*---------------------------------------
  VARIABLES & RESET
---------------------------------------*/
:root {
    --color-primary: #2563eb;
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
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --font-sans: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/*---------------------------------------
  LAYOUT PRINCIPAL
---------------------------------------*/
.google-wrapper {
    padding: 2rem 1.5rem;
    min-height: 100vh;
    background-color: var(--color-gray-50);
}

.google-card {
    max-width: 1440px;
    margin: 0 auto;
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    padding: 1.75rem 1.5rem;
}

@media (min-width: 640px) {
    .google-card {
        padding: 2rem 2rem;
    }
}

@media (min-width: 1024px) {
    .google-card {
        padding: 2.5rem 2.5rem;
    }
}

/*---------------------------------------
  HEADER
---------------------------------------*/
.gs-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (min-width: 768px) {
    .gs-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.gs-title {
    font-size: 1.875rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--color-gray-800);
    margin: 0 0 0.25rem 0;
    letter-spacing: -0.025em;
}

@media (min-width: 640px) {
    .gs-title {
        font-size: 2.25rem;
    }
}

.gs-subtitle {
    font-size: 1rem;
    color: var(--color-gray-500);
    margin: 0;
    font-weight: 400;
}

@media (min-width: 640px) {
    .gs-subtitle {
        font-size: 1.125rem;
    }
}

/*---------------------------------------
  ALERTES
---------------------------------------*/
.gs-alert-success {
    background-color: var(--color-success-light);
    color: #065f46;
    padding: 1rem 1.25rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
    border-left: 4px solid var(--color-success);
}

.gs-alert-success i {
    font-size: 1.25rem;
}

/*---------------------------------------
  FORMULAIRE DE RECHERCHE
---------------------------------------*/
.gs-form {
    margin-bottom: 2rem;
}

.gs-search-box {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (min-width: 480px) {
    .gs-search-box {
        flex-direction: row;
        align-items: stretch;
    }
}

.gs-search-box input {
    flex: 1 1 0%;
    padding: 0.875rem 1.25rem;
    border: 1px solid var(--color-gray-300);
    border-radius: var(--radius-lg);
    font-size: 1rem;
    transition: all 0.2s ease;
    background-color: white;
    width: 100%;
}

.gs-search-box input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.gs-search-box button {
    background-color: var(--color-primary);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    padding: 0.875rem 1.75rem;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
    width: 100%;
}

@media (min-width: 480px) {
    .gs-search-box button {
        width: auto;
    }
}

.gs-search-box button:hover {
    background-color: #1d4ed8;
}

.gs-search-box button i {
    font-size: 1rem;
}

/*---------------------------------------
  TOOLBAR
---------------------------------------*/
.gs-toolbar {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin: 1.5rem 0 1.25rem;
}

@media (min-width: 640px) {
    .gs-toolbar {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.gs-count {
    font-size: 0.9375rem;
    color: var(--color-gray-600);
    background-color: var(--color-gray-100);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-lg);
    display: inline-flex;
    align-items: center;
    font-weight: 500;
}

.gs-count span {
    font-weight: 700;
    color: var(--color-gray-800);
    margin-right: 0.25rem;
}

/*---------------------------------------
  BOUTONS
---------------------------------------*/
.gs-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    font-size: 0.9375rem;
    font-weight: 600;
    border-radius: var(--radius-lg);
    transition: all 0.2s ease;
    cursor: pointer;
    border: 1px solid transparent;
    text-decoration: none;
    line-height: 1.5;
}

.gs-btn-danger {
    background-color: white;
    border-color: var(--color-gray-300);
    color: var(--color-danger);
}

.gs-btn-danger:hover:not(:disabled) {
    background-color: var(--color-danger-light);
    border-color: var(--color-danger);
}

.gs-btn-danger:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: white;
    border-color: var(--color-gray-200);
    color: var(--color-gray-400);
}

.gs-btn-pdf {
    background-color: white;
    border: 1px solid var(--color-gray-300);
    color: var(--color-danger);
    padding: 0.625rem 1.25rem;
}

.gs-btn-pdf:hover {
    background-color: var(--color-danger-light);
    border-color: var(--color-danger);
}

.gs-btn i {
    font-size: 0.9375rem;
}

/*---------------------------------------
  TABLEAU
---------------------------------------*/
.gs-table-wrapper {
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-200);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1.5rem;
    background-color: white;
}

.gs-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9375rem;
    min-width: 700px;
}

.gs-table thead {
    background-color: var(--color-gray-50);
    border-bottom: 2px solid var(--color-gray-200);
}

.gs-table th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-weight: 600;
    color: var(--color-gray-700);
    white-space: nowrap;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.gs-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--color-gray-200);
    vertical-align: middle;
    color: var(--color-gray-700);
}

.gs-table tbody tr:last-child td {
    border-bottom: none;
}

.gs-table tbody tr:hover {
    background-color: var(--color-gray-50);
    transition: background-color 0.15s ease;
}

.gs-strong {
    font-weight: 600;
    color: var(--color-gray-800);
}

.gs-muted {
    color: var(--color-gray-400);
}

/*---------------------------------------
  CHECKBOXES
---------------------------------------*/
.gs-table input[type="checkbox"] {
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
.gs-link {
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 500;
    word-break: break-all;
    transition: color 0.2s ease;
}

.gs-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/*---------------------------------------
  PAGINATION
---------------------------------------*/
.gs-pagination {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.pg {
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

.pg:hover:not(.active):not(.disabled) {
    background-color: var(--color-gray-50);
    border-color: var(--color-gray-400);
}

.pg.active {
    background-color: var(--color-primary);
    border-color: var(--color-primary);
    color: white;
}

.pg.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    background-color: var(--color-gray-100);
}

/*---------------------------------------
  ÉTAT VIDE
---------------------------------------*/
.gs-empty {
    text-align: center;
    padding: 3rem 1.5rem;
    background-color: var(--color-gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-200);
}

.gs-empty i {
    font-size: 3rem;
    color: var(--color-gray-400);
    margin-bottom: 1rem;
}

.gs-empty h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-gray-700);
    margin: 0.5rem 0 0;
}

/*---------------------------------------
  UTILITAIRES
---------------------------------------*/
.gs-form {
    width: 100%;
}

.gs-badge-success{
    background:#d1fae5;
    color:#065f46;
    padding:6px 10px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.gs-badge-pending{
    background:#fef3c7;
    color:#92400e;
    padding:6px 10px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}
.gs-loader{
    margin-top:20px;
    text-align:center;
}

.spinner{
    width:40px;
    height:40px;
    border:4px solid #e5e7eb;
    border-top:4px solid #2563eb;
    border-radius:50%;
    animation: spin 1s linear infinite;
    margin:0 auto 10px;
}

@keyframes spin{
    to{ transform:rotate(360deg);}
}

.hidden{ display:none; }

</style>
<script>
    const form = document.querySelector('.gs-form');
const loader = document.getElementById('loader');

if(form){
    form.addEventListener('submit',function(){
        loader.classList.remove('hidden');
    });
}

</script>
@endsection