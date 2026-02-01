<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
</head>
<body>

<h2>Connexion Admin</h2>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form method="POST" action="/admin/login">
    @csrf

    <input type="email" name="email" placeholder="Email admin"><br><br>
    <input type="password" name="password" placeholder="Mot de passe"><br><br>

    <button type="submit">Connexion</button>
</form>

<hr>
<p><strong>Compte admin :</strong></p>
<p>Email : admin@test.com</p>
<p>Mot de passe : admin123</p>

</body>
</html>
