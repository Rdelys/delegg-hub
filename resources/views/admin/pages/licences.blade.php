@extends('admin.layout')

@section('title', 'Licences')

@section('content')

<div class="licences-container">

    <!-- ===== HEADER ===== -->
    <div class="top-header">
        <div>
            <h1>Licences</h1>
            <p>Gestion des licences clients</p>
        </div>

        <button onclick="toggleForm()" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvelle licence
        </button>
    </div>

    <!-- ===== FORMULAIRE ===== -->
    <div id="licenceForm" class="card hidden">
        <h2>Nouvelle licence</h2>

        <form method="POST" action="{{ route('admin.licences.store') }}">
            @csrf

            <div class="form-grid">
                <select name="client_id" required>
                    <option value="">Sélectionner une entreprise</option>
                    @foreach($clients as $client)
                        @if($client->company)
                            <option value="{{ $client->id }}">
                                {{ $client->company }}
                            </option>
                        @endif
                    @endforeach
                </select>

                <input type="date" name="start_date" required>
                <input type="date" name="end_date" required>
            </div>


            <div class="form-actions">
                <button type="button" onclick="toggleForm()" class="btn-light">
                    Annuler
                </button>
                <button type="submit" class="btn-primary">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <!-- ===== TABLEAU (STATIQUE) ===== -->
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($licences as $licence)
                        <tr>
                            <td>{{ $licence->client->company }}</td>
                            <td>{{ $licence->start_date->format('Y-m-d') }}</td>
                            <td>{{ $licence->end_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge badge-{{ $licence->status }}">
                                    {{ $licence->status === 'actif' ? 'Active' : 'Expirée' }}
                                </span>
                            </td>
                            <td class="actions">
                                <form method="POST" action="{{ route('admin.licences.destroy', $licence) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-light">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>

<style>
.licences-container{
    max-width:1400px;
    margin:auto;
    padding:30px 20px;
}

.top-header{
    background:linear-gradient(135deg,#4f46e5,#6366f1);
    color:white;
    padding:30px;
    border-radius:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.btn-primary{
    background:white;
    color:#4338ca;
    padding:12px 20px;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
}

.btn-light{
    background:#e5e7eb;
    padding:12px 20px;
    border:none;
    border-radius:12px;
    cursor:pointer;
}

.card{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:25px;
    box-shadow:0 15px 40px rgba(0,0,0,.06);
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.form-grid input,
.form-grid select{
    padding:14px;
    border-radius:12px;
    border:1px solid #e5e7eb;
}

.form-actions{
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:800px;
}

th,td{
    padding:16px;
    border-bottom:1px solid #e5e7eb;
}

th{
    background:#f8fafc;
    text-align:left;
}

.badge{
    padding:6px 14px;
    border-radius:999px;
    font-size:.85rem;
    font-weight:600;
}

.badge-active{
    background:rgba(22,163,74,.15);
    color:#16a34a;
}

.badge-expired{
    background:rgba(220,38,38,.15);
    color:#dc2626;
}

.actions{
    display:flex;
    gap:8px;
}

.hidden{ display:none; }
</style>

<script>
function toggleForm() {
    document.getElementById('licenceForm').classList.toggle('hidden');
}
</script>

@endsection
