<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Licence activée</title>
</head>
<body style="font-family:Arial, sans-serif; background:#f4f6fb; padding:30px">

    <div style="max-width:600px; margin:auto; background:#ffffff; padding:30px; border-radius:10px">

        <h2 style="color:#4f46e5">🎉 Votre licence est active</h2>

        <p>Bonjour {{ $client->first_name ?? 'Client' }},</p>

        <p>
            Nous vous confirmons que votre <strong>compte est désormais actif</strong>
            et qu’une <strong>licence</strong> a été activée pour votre entreprise :
        </p>

        <p>
            <strong>🏢 Entreprise :</strong> {{ $client->company }}<br>
            <strong>📅 Début :</strong> {{ $licence->start_date->format('d/m/Y') }}<br>
            <strong>📅 Fin :</strong> {{ $licence->end_date->format('d/m/Y') }}
        </p>

        <p>
            Votre accès restera actif jusqu’à la date de fin de la licence.
        </p>

        <p style="margin-top:30px">
            Cordialement,<br>
            <strong>L’équipe support</strong>
        </p>

    </div>

</body>
</html>
