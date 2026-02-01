<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Delegg Hub | Dashboard Admin</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .box {
            background: #1e293b;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
        }

        p {
            color: #9ca3af;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Dashboard Admin</h1>
    <p>Bienvenue sur Delegg Hub</p>
</div>
<form method="POST" action="/admin/logout" style="margin-top:20px;">
    @csrf
    <button type="submit">Se déconnecter</button>
</form>

</body>
</html>
