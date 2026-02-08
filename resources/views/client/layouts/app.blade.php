<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Delegg Hub')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --text-light: #e5e7eb;
            --text-muted: #94a3b8;
            --bg: #f1f5f9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: #0f172a;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--text-light);
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 40px;
            text-align: center;
        }

        .brand span {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 4px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }

        .menu li {
            margin-bottom: 6px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s, transform 0.2s;
        }

        .menu a i {
            width: 18px;
            text-align: center;
            color: var(--text-muted);
        }

        .menu a:hover {
            background: var(--sidebar-hover);
            transform: translateX(3px);
        }

        .menu a:hover i {
            color: #fff;
        }

        .menu a.active {
            background: var(--primary);
        }

        .menu a.active i {
            color: #fff;
        }

        /* LOGOUT */
        .logout {
            margin-top: 20px;
        }

        .logout button {
            width: 100%;
            background: transparent;
            border: 1px solid #334155;
            color: var(--text-light);
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
        }

        .logout button:hover {
            background: #334155;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 40px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        h1, h2 {
            margin-top: 0;
        }

        h1 {
            font-size: 26px;
        }

        h2 {
            font-size: 20px;
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">
            DELEGG HUB
            <span>{{ session('client.company') }}</span>
        </div>

        <ul class="menu">
            <li>
                <a href="{{ route('client.home') }}" class="{{ request()->routeIs('client.home') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    Accueil
                </a>
            </li>

            <li>
                <a href="{{ route('client.profil') }}" class="{{ request()->routeIs('client.profil') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                    Profil
                </a>
            </li>

            <li>
                <a href="{{ route('client.insee') }}" class="{{ request()->routeIs('client.insee') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    INSEE
                </a>
            </li>

            <li>
                <a href="{{ route('client.chambre') }}" class="{{ request()->routeIs('client.chambre') ? 'active' : '' }}">
                    <i class="fa-solid fa-building-columns"></i>
                    Chambre de Métiers
                </a>
            </li>

            <li>
                <a href="{{ route('client.google') }}" class="{{ request()->routeIs('client.google') ? 'active' : '' }}">
                    <i class="fa-brands fa-google"></i>
                    Google
                </a>
            </li>

            <li>
                <a href="{{ route('client.web') }}" class="{{ request()->routeIs('client.web') ? 'active' : '' }}">
<i class="fa-solid fa-earth-americas"></i>
                    Site Web
                </a>
            </li>

            @if(session('client.role') === 'superadmin')
                <li>
                    <a href="{{ route('client.users') }}" class="{{ request()->routeIs('client.users') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        Utilisateurs
                    </a>
                </li>
            @endif
        </ul>

        <div class="logout">
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Déconnexion
                </button>
            </form>
        </div>

    </aside>

    <!-- CONTENT -->
    <main class="content">
        @yield('content')
    </main>

</div>

</body>
</html>
