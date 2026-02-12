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

{{-- STYLE PREMIUM + RESPONSIVE --}}
<style>
.web-scraper {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --border: #e5e7eb;
    --muted: #94a3b8;
}

/* HEADER */
.ws-header {
    margin-bottom: 32px;
}
.ws-title {
    margin: 0 0 6px;
    font-size: 28px;
}
.ws-subtitle {
    margin: 0;
    color: #64748b;
}

/* ALERT */
.ws-alert-success {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #ecfdf5;
    border: 1px solid #34d399;
    color: #065f46;
    padding: 14px 18px;
    border-radius: 16px;
    margin-bottom: 28px;
    font-weight: 600;
}

/* FORM */
.ws-form {
    background: linear-gradient(180deg, #f8fafc, #f1f5f9);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 26px;
    margin-bottom: 44px;
}
.ws-label {
    font-weight: 600;
    display: block;
    margin-bottom: 12px;
}
.ws-form-row {
    display: flex;
    gap: 14px;
}
.ws-input {
    flex: 1;
    padding: 15px 18px;
    border-radius: 16px;
    border: 1px solid #cbd5f5;
}
.ws-input:focus {
    outline: none;
    border-color: var(--primary);
}
.ws-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--primary);
    color: #fff;
    border-radius: 16px;
    padding: 0 26px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}
.ws-button:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}
.ws-hint {
    margin-top: 12px;
    color: var(--muted);
    font-size: 13px;
}

/* TABLE */
.ws-section-title {
    margin-bottom: 16px;
}
.ws-table-wrapper {
    overflow-x: auto;
}
.ws-table {
    width: 100%;
    border-collapse: collapse;
}
.ws-table thead {
    background: #f1f5f9;
}
.ws-table th,
.ws-table td {
    padding: 15px;
}
.ws-table tbody tr {
    border-bottom: 1px solid var(--border);
}
.ws-strong {
    font-weight: 600;
}
.ws-email {
    color: #2563eb;
}
.ws-link {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}
.ws-empty {
    padding: 26px;
    text-align: center;
    color: var(--muted);
}

/* PAGINATION */
.ws-pagination {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 28px;
}
.ws-pagination .page {
    min-width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: var(--primary);
    border: 1px solid var(--border);
    text-decoration: none;
}
.ws-pagination .page:hover {
    background: #eef2ff;
}
.ws-pagination .active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}
.ws-pagination .disabled {
    opacity: 0.4;
    cursor: default;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .ws-form-row {
        flex-direction: column;
    }
    .ws-button {
        width: 100%;
        justify-content: center;
        padding: 14px;
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
