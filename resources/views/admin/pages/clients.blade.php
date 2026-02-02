@extends('admin.layout')

@section('title', 'Clients')

@section('content')

<div class="clients-container">

    <!-- ===== HEADER ===== -->
    <div class="top-header">
        <div>
            <h1>Clients</h1>
            <p>Gestion professionnelle des utilisateurs</p>
        </div>

        <button onclick="toggleForm()" class="btn-primary">
            <i class="fas fa-user-plus"></i>
            Nouveau client
        </button>
    </div>

    <!-- ===== FORMULAIRE ===== -->
    <div id="clientFormContainer" class="card hidden">
        <h2 id="formTitle">Nouveau client</h2>

        <form id="clientForm" method="POST" action="{{ route('admin.clients.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="form-grid">
                <input name="firstName" placeholder="Prénom">
                <input name="lastName" placeholder="Nom">
                <input name="email" type="email" placeholder="Email">
                <input name="phone" placeholder="Téléphone">
                <input name="address" placeholder="Adresse">
                <input name="company" placeholder="Entreprise">
            </div>

            <div class="form-actions">
                <button type="button" onclick="toggleForm()" class="btn-light">Annuler</button>
                <button type="submit" class="btn-primary" id="submitBtn">Ajouter</button>
            </div>
        </form>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->first_name }}</td>
                            <td>{{ $client->last_name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>{{ $client->address }}</td>
                            <td>{{ $client->company }}</td>
                            <td>
                                <span class="badge badge-{{ $client->status }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                            <td class="actions">

                                <!-- EDIT -->
                                <button class="btn-light"
                                    onclick="editClient(
                                        {{ $client->id }},
                                        '{{ $client->first_name }}',
                                        '{{ $client->last_name }}',
                                        '{{ $client->email }}',
                                        '{{ $client->phone }}',
                                        '{{ $client->address }}',
                                        '{{ $client->company }}'
                                    )">
                                    ✏️
                                </button>

                                <!-- DELETE -->
                                <form method="POST"
                                      action="{{ route('admin.clients.destroy', $client) }}"
                                      style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-light" onclick="return confirm('Supprimer ce client ?')">
                                        🗑️
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;">Aucun client</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>


<style>
/* ===== THEME ===== */
:root{
    --primary:#4f46e5;
    --primary-dark:#4338ca;
    --success:#16a34a;
    --danger:#dc2626;
    --bg:#f4f6fb;
    --border:#e5e7eb;
    --dark:#0f172a;
}

body{ background:var(--bg); }

/* ===== CONTAINER ===== */
.clients-container{
    max-width:1400px;
    margin:auto;
    padding:30px 20px;
}

/* ===== TOP HEADER ===== */
.top-header{
    background:linear-gradient(135deg,var(--primary),#6366f1);
    color:white;
    padding:30px;
    border-radius:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    gap:20px;
}

.top-header h1{
    margin:0;
    font-size:2.3rem;
}

.top-header p{
    margin:5px 0 0;
    opacity:.9;
}

/* ===== BUTTONS ===== */
.btn-primary{
    background:white;
    color:var(--primary-dark);
    padding:12px 20px;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:8px;
}

.btn-primary:hover{
    background:#eef2ff;
}

.btn-light{
    background:#e5e7eb;
    border:none;
    padding:12px 20px;
    border-radius:12px;
    cursor:pointer;
}

/* ===== CARD ===== */
.card{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:25px;
    box-shadow:0 15px 40px rgba(0,0,0,.06);
}

/* ===== FORM ===== */
.form-header{
    margin-bottom:15px;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.form-grid input{
    padding:14px;
    border-radius:12px;
    border:1px solid var(--border);
}

.form-actions{
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:20px;
}

/* ===== TABLE ===== */
.table-header{
    margin-bottom:15px;
}

.table-header input{
    width:100%;
    max-width:320px;
    padding:12px;
    border-radius:12px;
    border:1px solid var(--border);
}

.table-responsive{ overflow-x:auto; }

table{
    width:100%;
    border-collapse:collapse;
    min-width:1000px;
}

th,td{
    padding:16px;
    border-bottom:1px solid var(--border);
    vertical-align:top;
}

th{
    background:#f8fafc;
    text-align:left;
    font-weight:600;
}

/* ===== STATUS ===== */
.badge{
    padding:6px 14px;
    border-radius:999px;
    font-size:.85rem;
    font-weight:600;
}

.badge-actif{
    background:rgba(22,163,74,.15);
    color:var(--success);
}

.badge-inactif{
    background:rgba(220,38,38,.15);
    color:var(--danger);
}

/* ===== ACTIONS ===== */
.actions{
    display:flex;
    gap:8px;
}

/* ===== UTILS ===== */
.hidden{ display:none; }

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
    .top-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .btn-primary{
        width:100%;
        justify-content:center;
    }
}
</style>

<script>
function toggleForm() {
    document.getElementById('clientFormContainer').classList.toggle('hidden');
}

function editClient(id, first, last, email, phone, address, company) {

    const form = document.getElementById('clientForm');

    form.action = `/admin/clients/${id}`;
    document.getElementById('formMethod').value = 'PUT';

    form.firstName.value = first;
    form.lastName.value = last;
    form.email.value = email;
    form.phone.value = phone;
    form.address.value = address;
    form.company.value = company;

    document.getElementById('formTitle').innerText = 'Modifier le client';
    document.getElementById('submitBtn').innerText = 'Mettre à jour';

    document.getElementById('clientFormContainer').classList.remove('hidden');
}
</script>

@endsection
