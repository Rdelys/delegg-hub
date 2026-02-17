<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Web Scraper – Export PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #0f172a;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        th {
            background: #f1f5f9;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Résultats Web Scraper</h1>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Facebook</th>
            <th>Instagram</th>
            <th>LinkedIn</th>
            <th>Source</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->facebook }}</td>
                <td>{{ $row->instagram }}</td>
                <td>{{ $row->linkedin }}</td>
                <td>{{ $row->source_url }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
