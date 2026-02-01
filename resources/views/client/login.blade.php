<!DOCTYPE html>
<html>
<head>
    <title>Login Client</title>
</head>
<body>

<h2>Connexion Client</h2>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form method="POST" action="/login">
    @csrf

    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Mot de passe"><br><br>

    <button type="submit">Connexion</button>
</form>

<hr>
<p><strong>Compte client :</strong></p>
<p>Email : client@test.com</p>
<p>Mot de passe : 1234</p>

<hr>

</body>
</html>
