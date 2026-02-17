<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Google Maps – Export PDF</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            text-align: left;
            padding: 6px;
            font-weight: bold;
            border: 1px solid #d1d5db;
        }

        td {
            padding: 6px;
            border: 1px solid #d1d5db;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }
    </style>
</head>
<body>

<h1>Entreprises – Google Maps</h1>

<table>
    <thead>
        <tr>
            <th>Entreprise</th>
            <th>Catégorie</th>
            <th>Adresse</th>
            <th>Téléphone</th>
            <th>Site</th>
            <th>Note</th>
            <th>Avis</th>
            <th>Statut Web</th>
        </tr>
    </thead>
    <tbody>
        @foreach($places as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category }}</td>
                <td>{{ $p->address }}</td>
                <td>{{ $p->phone }}</td>
                <td>{{ $p->website }}</td>
                <td>{{ $p->rating }}</td>
                <td>{{ $p->reviews_count }}</td>
                <td>
                    @if($p->website_scraped)
                        Scrappé
                    @else
                        —
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
