@extends('client.layouts.app')

@section('title', 'Utilisateurs')

@section('content')

@if(session('success'))
    <div class="card" style="border-left:4px solid #22c55e;margin-bottom:20px">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #ef4444;margin-bottom:20px">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <h2>Ajouter un utilisateur</h2>

    <form method="POST" action="{{ route('client.users.store') }}"
          style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px">
        @csrf

        <input name="first_name" placeholder="Prénom" required>
        <input name="last_name" placeholder="Nom" required>
        <input name="email" placeholder="Email" required>
        
        <select name="role" required>
            <option value="">Rôle</option>
            <option value="simpleutilisateur">Utilisateur</option>
            <option value="superadmin">Super admin</option>
        </select>

        <div style="grid-column:1/3">
            <button type="submit">
                <i class="fa-solid fa-user-plus"></i> Ajouter utilisateur
            </button>
        </div>
    </form>
</div>

<div class="card" style="margin-top:30px">
    <h2>Utilisateurs de l’entreprise</h2>

    <table style="width:100%;margin-top:20px;border-collapse:collapse">
        <thead>
            <tr style="text-align:left;color:#64748b">
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @foreach($users as $user)
            <tr style="border-top:1px solid #e5e7eb">
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <form method="POST" action="{{ route('client.users.delete', $user) }}"
                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button style="background:#ef4444">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<style>
    input, select {
        padding:12px;
        border-radius:10px;
        border:1px solid #cbd5f5;
        background:#f8fafc;
    }

    button {
        padding:12px 18px;
        border-radius:10px;
        border:none;
        background:#4f46e5;
        color:white;
        font-weight:600;
        cursor:pointer;
    }

    button:hover {
        background:#4338ca;
    }
</style>

@endsection
