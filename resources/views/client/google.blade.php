@extends('client.layouts.app')

@section('title', 'Google Maps')

@section('content')
<div class="card google-scraper">

    {{-- HEADER --}}
    <div class="gs-header">
        <div class="gs-header-left">
            <h1 class="gs-title">Google Maps</h1>
            <p class="gs-subtitle">
                Recherche d'entreprises locales depuis Google Maps
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

        <label class="gs-label">Recherche</label>

        <div class="gs-form-row">
            <input
                type="text"
                name="query"
                required
                placeholder="Ex : plombier Paris"
                class="gs-input"
                value="{{ old('query') }}"
            >

            <button type="submit" class="gs-btn gs-btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i>
                Analyser
            </button>
        </div>

        <p class="gs-hint">
            Exemple : plombier Paris, restaurant Lyon, avocat Marseille
        </p>
    </form>

    {{-- RESULTS --}}
    @if(isset($places) && $places->count())

        <h2 class="gs-section-title">Résultats</h2>

        <div class="gs-table-wrapper">
            <table class="gs-table">
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Catégorie</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Site</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($places as $p)
                        <tr>
                            <td class="gs-strong">{{ $p->name ?? '—' }}</td>
                            <td>{{ $p->category ?? '—' }}</td>
                            <td>{{ $p->address ?? '—' }}</td>
                            <td class="gs-phone">{{ $p->phone ?? '—' }}</td>
                            <td>
                                @if($p->website)
                                    <a href="{{ $p->website }}" target="_blank" class="gs-link">
                                        Ouvrir
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                @else
                                    <span class="gs-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($places->hasPages())
            <div class="gs-pagination">

                @if ($places->onFirstPage())
                    <span class="page disabled">‹</span>
                @else
                    <a href="{{ $places->previousPageUrl() }}" class="page">‹</a>
                @endif

                @foreach (
                    $places->getUrlRange(
                        max(1, $places->currentPage() - 2),
                        min($places->lastPage(), $places->currentPage() + 2)
                    ) as $page => $url
                )
                    @if ($page == $places->currentPage())
                        <span class="page active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($places->hasMorePages())
                    <a href="{{ $places->nextPageUrl() }}" class="page">›</a>
                @else
                    <span class="page disabled">›</span>
                @endif

            </div>
        @endif

    @else
        <div class="gs-empty-state">
            <i class="fa-solid fa-magnifying-glass"></i>
            <h3>Aucun résultat</h3>
            <p>Saisissez une recherche pour analyser Google Maps</p>
        </div>
    @endif

</div>

<style>
/* VARIABLES */
.google-scraper {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-light: #eef2ff;
    --border: #e5e7eb;
    --muted: #94a3b8;
    --bg: #f8fafc;
}

/* HEADER */
.gs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}
.gs-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 6px;
}
.gs-subtitle {
    margin: 0;
    color: #64748b;
}

/* BUTTONS */
.gs-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 44px;
    padding: 0 20px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.gs-btn-primary {
    background: var(--primary);
    color: #fff;
}
.gs-btn-primary:hover {
    background: var(--primary-dark);
}
.gs-btn-pdf {
    background: #fff;
    color: #dc2626;
    border: 1px solid #fecaca;
}
.gs-btn-pdf:hover {
    background: #fee2e2;
}

/* ALERT */
.gs-alert-success {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #ecfdf5;
    border: 1px solid #34d399;
    color: #065f46;
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 28px;
}

/* FORM */
.gs-form {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 40px;
}
.gs-label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}
.gs-form-row {
    display: flex;
    gap: 14px;
}
.gs-input {
    flex: 1;
    padding: 14px 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
}
.gs-hint {
    margin-top: 10px;
    font-size: 13px;
    color: var(--muted);
}

/* TABLE */
.gs-table-wrapper {
    overflow-x: auto;
}
.gs-table {
    width: 100%;
    border-collapse: collapse;
}
.gs-table th,
.gs-table td {
    padding: 14px;
    border-bottom: 1px solid var(--border);
}
.gs-table th {
    background: #f1f5f9;
    text-align: left;
}
.gs-strong {
    font-weight: 600;
}
.gs-link {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}
.gs-muted {
    color: var(--muted);
}

/* EMPTY */
.gs-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
}

/* PAGINATION */
.gs-pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 30px;
}
.gs-pagination .page {
    min-width: 38px;
    height: 38px;
    padding: 0 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: #fff;
    font-weight: 600;
    color: var(--primary);
    text-decoration: none;
}
.gs-pagination .page:hover {
    background: var(--primary-light);
}
.gs-pagination .active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}
.gs-pagination .disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .gs-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
    }
    .gs-form-row {
        flex-direction: column;
    }
    .gs-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
