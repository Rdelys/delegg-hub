<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Google Maps – Export PDF</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 20px;
            color: #6b7280;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #d1d5db;
        }

        td {
            padding: 8px;
            border: 1px solid #d1d5db;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }
    </style>
</head>
<body>

    <h1>Entreprises – Google Maps</h1>
    <p>Export généré automatiquement</p>

    <table>
        <thead>
            <tr>
                <th>Entreprise</th>
                <th>Catégorie</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Site</th>
            </tr>
        </thead>
        <tbody>
            @foreach($places as $p)
                <tr>
                    <td>{{ $p->name ?? '—' }}</td>
                    <td>{{ $p->category ?? '—' }}</td>
                    <td>{{ $p->address ?? '—' }}</td>
                    <td>{{ $p->phone ?? '—' }}</td>
                    <td>{{ $p->website ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
