<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Delegg Hub | Admin')</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: #020617;
            display: flex;
            flex-direction: column;
            padding: 24px;
        }

        .brand {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 40px;
        }

        .menu a {
            display: block;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-radius: 10px;
            color: #c7d2fe;
            text-decoration: none;
            transition: 0.3s;
        }

        .menu a:hover,
        .menu a.active {
            background: #1e293b;
            color: #fff;
        }

        .logout {
            margin-top: auto;
        }

        .logout button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: #7c3aed;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        /* Content */
        .content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .card {
            background: #1e293b;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand">Delegg Hub</div>

        <nav class="menu">
            <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="/admin/tdb" class="{{ request()->is('admin/tdb') ? 'active' : '' }}">TDB</a>
            <a href="/admin/clients" class="{{ request()->is('admin/clients') ? 'active' : '' }}">Clients</a>
            <a href="/admin/licences" class="{{ request()->is('admin/licences') ? 'active' : '' }}">Licences</a>
            <a href="/admin/users" class="{{ request()->is('admin/users') ? 'active' : '' }}">Users Client</a>
        </nav>

        <div class="logout">
            <form method="POST" action="/admin/logout">
                @csrf
                <button type="submit">Se déconnecter</button>
            </form>
        </div>
    </aside>

    <main class="content">
        @yield('content')
    </main>

</body>
</html>
