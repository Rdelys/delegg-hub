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

/* BACKGROUND PREMIUM */
.google-wrapper {
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    padding: 40px;
    min-height: 100vh;
}

/* CARD */
.google-card {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

/* HEADER */
.gs-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.gs-title {
    font-size:32px;
    font-weight:800;
    background: linear-gradient(90deg,#4f46e5,#7c3aed);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.gs-subtitle {
    color:#64748b;
}

/* SEARCH */
.gs-search-box {
    display:flex;
    background:#fff;
    border-radius:14px;
    box-shadow:0 8px 20px rgba(0,0,0,0.05);
    overflow:hidden;
}

.gs-search-box input {
    flex:1;
    padding:16px;
    border:none;
    outline:none;
}

.gs-search-box button {
    background:#4f46e5;
    color:#fff;
    border:none;
    padding:0 22px;
    cursor:pointer;
}

/* TOOLBAR */
.gs-toolbar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:25px 0 15px;
}

/* BUTTONS */
.gs-btn {
    padding:10px 18px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    border:none;
}

.gs-btn-danger {
    background:#dc2626;
    color:#fff;
}

.gs-btn-danger:disabled {
    opacity:0.4;
    cursor:not-allowed;
}

.gs-btn-pdf {
    background:#fff;
    border:1px solid #fecaca;
    color:#dc2626;
}

/* TABLE */
.gs-table {
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:15px;
    overflow:hidden;
}

.gs-table th {
    background:#f1f5f9;
    padding:16px;
    text-align:left;
}

.gs-table td {
    padding:16px;
    border-bottom:1px solid #e5e7eb;
}

.gs-table tr:hover {
    background:#f9fafb;
    transition:0.2s;
}

/* LINKS */
.gs-link {
    color:#4f46e5;
    font-weight:600;
}

/* PAGINATION PREMIUM */
.gs-pagination {
    margin-top:30px;
    display:flex;
    justify-content:center;
    gap:8px;
}

.pg {
    width:42px;
    height:42px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    background:#fff;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    text-decoration:none;
    font-weight:600;
    color:#374151;
    transition:0.3s;
}

.pg:hover {
    background:#4f46e5;
    color:#fff;
    transform:translateY(-3px);
}

.pg.active {
    background:#4f46e5;
    color:#fff;
}

.pg.disabled {
    opacity:0.3;
    pointer-events:none;
}

.gs-empty {
    text-align:center;
    padding:60px;
    color:#64748b;
}

</style>

@endsection
