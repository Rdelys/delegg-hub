@extends('admin.layout')

@section('title', 'Clients')

@section('content')

<div class="clients-container">

    <!-- ===== PREMIUM TOP HEADER ===== -->
    <div class="top-header">
        <div class="top-left">
            <h1>Clients</h1>
            <p>Gestion professionnelle des utilisateurs</p>
        </div>

        <button id="newClientBtn" class="btn-primary">
            <i class="fas fa-user-plus"></i>
            Nouveau client
        </button>
    </div>

    <!-- ===== FORM (AU-DESSUS DU TABLEAU) ===== -->
    <div id="clientFormContainer" class="card hidden">
        <div class="form-header">
            <h2 id="formTitle">Nouveau client</h2>
        </div>

        <form id="clientForm">
            <div class="form-grid">
                <input name="firstName" placeholder="Prénom *" required>
                <input name="lastName" placeholder="Nom *" required>
                <input name="address" placeholder="Adresse *" required>
                <input name="phone" placeholder="Téléphone *" required>
                <input name="company" placeholder="Entreprise *" required>
            </div>

            <div class="form-actions">
                <button type="button" id="cancelBtn" class="btn-light">Annuler</button>
                <button type="submit" class="btn-primary" id="submitBtn">Ajouter</button>
            </div>
        </form>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card">
        <div class="table-header">
            <input id="searchInput" placeholder="Rechercher un client...">
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Entreprise</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody"></tbody>
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

/* ===== LAYOUT ===== */
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
    min-width:800px;
}

th,td{
    padding:16px;
    border-bottom:1px solid var(--border);
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
let clients = [
    {id:1, firstName:'Marie', lastName:'Dubois', company:'Tech', phone:'0102030405', address:'Paris', status:'actif'},
    {id:2, firstName:'Paul', lastName:'Martin', company:'WebPro', phone:'0607080910', address:'Lyon', status:'inactif'},
];

let editId = null;

const body = document.getElementById('clientsTableBody');
const formBox = document.getElementById('clientFormContainer');
const form = document.getElementById('clientForm');
const search = document.getElementById('searchInput');
const formTitle = document.getElementById('formTitle');
const submitBtn = document.getElementById('submitBtn');

function render(){
    body.innerHTML='';
    clients
        .filter(c =>
            c.firstName.toLowerCase().includes(search.value.toLowerCase()) ||
            c.lastName.toLowerCase().includes(search.value.toLowerCase()) ||
            c.company.toLowerCase().includes(search.value.toLowerCase())
        )
        .forEach(c=>{
            body.innerHTML+=`
            <tr>
                <td>${c.id}</td>
                <td>${c.firstName}</td>
                <td>${c.lastName}</td>
                <td>${c.company}</td>
                <td>${c.phone}</td>
                <td>
                    <span class="badge badge-${c.status}">
                        ${c.status === 'actif' ? 'Actif' : 'Inactif'}
                    </span>
                </td>
                <td class="actions">
                    <button class="btn-light" onclick="editClient(${c.id})">✏️</button>
                    <button class="btn-light" onclick="deleteClient(${c.id})">🗑️</button>
                </td>
            </tr>`;
        });
}

render();

document.getElementById('newClientBtn').onclick = () => {
    form.reset();
    editId = null;
    formTitle.textContent = 'Nouveau client';
    submitBtn.textContent = 'Ajouter';
    formBox.classList.remove('hidden');
};

document.getElementById('cancelBtn').onclick = () => {
    formBox.classList.add('hidden');
};

form.onsubmit = e => {
    e.preventDefault();
    const d = new FormData(form);

    if(editId){
        const c = clients.find(c => c.id === editId);
        Object.assign(c,{
            firstName:d.get('firstName'),
            lastName:d.get('lastName'),
            company:d.get('company'),
            phone:d.get('phone'),
            address:d.get('address'),
        });
    }else{
        clients.unshift({
            id: clients.length + 1,
            firstName:d.get('firstName'),
            lastName:d.get('lastName'),
            company:d.get('company'),
            phone:d.get('phone'),
            address:d.get('address'),
            status:'actif'
        });
    }

    form.reset();
    formBox.classList.add('hidden');
    render();
};

function editClient(id){
    const c = clients.find(c => c.id === id);
    editId = id;

    form.firstName.value = c.firstName;
    form.lastName.value = c.lastName;
    form.address.value = c.address;
    form.phone.value = c.phone;
    form.company.value = c.company;

    formTitle.textContent = 'Modifier le client';
    submitBtn.textContent = 'Mettre à jour';
    formBox.classList.remove('hidden');
}

function deleteClient(id){
    if(confirm('Supprimer ce client ?')){
        clients = clients.filter(c => c.id !== id);
        render();
    }
}

search.oninput = render;
</script>

@endsection
