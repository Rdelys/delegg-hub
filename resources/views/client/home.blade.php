<h1>
    Bienvenue {{ session('client.first_name') }}
</h1>

<p>Email : {{ session('client.email') }}</p>

<form method="POST" action="{{ route('client.logout') }}">
    @csrf
    <button type="submit">Déconnexion</button>
</form>
