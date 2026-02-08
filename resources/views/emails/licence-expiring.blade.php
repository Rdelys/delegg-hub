<h2>⚠️ Licence bientôt expirée</h2>

<p>Bonjour {{ $client->first_name ?? 'Client' }},</p>

<p>
    Votre licence pour <strong>{{ $client->company }}</strong>
    expirera dans <strong>{{ $daysLeft }} jour(s)</strong>.
</p>

<p>
    <strong>Date de fin :</strong>
    {{ $licence->end_date->format('d/m/Y') }}
</p>

<p>
    Pensez à renouveler votre licence pour éviter toute interruption.
</p>

<p>Cordialement,<br>Support</p>
