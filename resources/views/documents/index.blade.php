<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes documents déposés - Trésor Public du Bénin</title>
    <style>
        :root {
            --bg-color: #fcfbfa; /* Crème clair inspiré de la page de connexion */
            --card-bg: #ffffff;
            --primary-green: #113f2d; /* Vert foncé institutionnel */
            --primary-green-hover: #0b2b1e;
            --accent-gold: #c5a880;
            --text-main: #2b2b2b;
            --text-muted: #666666;
            --border-color: #e3dec3;
            --success-green: #1e8449;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-main);
            background: var(--bg-color);
            margin: 0;
            padding: 30px;
            line-height: 1.6;
        }

        h1 {
            color: var(--primary-green);
            font-size: 1.8rem;
            margin-bottom: 25px;
            font-weight: 700;
        }

        /* Sections / Conteneurs sous forme de cartes */
        .section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }

        /* Tableaux modernisés */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        th, td {
            border: 1px solid #eee;
            padding: 12px 15px;
            text-align: left;
        }

        th { 
            background: var(--primary-green); 
            color: #ffffff;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #faf8f5;
        }

        /* Boutons et liens d'action */
        .btn-primary {
            display: inline-block;
            cursor: pointer; 
            padding: 10px 16px; 
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background: var(--primary-green-hover);
        }

        a {
            color: var(--primary-green);
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }

        /* Messages de succès */
        .message-succes { 
            background: #e8f8f5;
            color: var(--success-green);
            border: 1px solid #a3e4d7;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px; 
        }

        /* Pagination Laravel (si personnalisée ou par défaut) */
        .pagination {
            margin-top: 20px;
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>

    @include('partials.nav')

    <h1>Mes documents déposés</h1>

    @if (session('succes'))
        <div class="message-succes">{{ session('succes') }}</div>
    @endif

    <div class="section">
        <a href="{{ route('documents.create') }}" class="btn-primary">+ Déposer un document</a>

        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Déposé le</th>
                    <th>Expiration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr>
                        <td><strong>{{ $document->reference }}</strong></td>
                        <td>{{ $document->titre }}</td>
                        <td>{{ $document->type_document }}</td>
                        <td>{{ $document->statut }}</td>
                        <td>{{ $document->date_depot->format('d/m/Y') }}</td>
                        <td>{{ $document->date_expiration?->format('d/m/Y') ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('documents.show', $document) }}" style="padding: 6px 12px; background: #f0f0f0; border-radius: 4px; border: 1px solid #ccc; font-size: 0.85rem;">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">Aucun document déposé pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $documents->links() }}
        </div>
    </div>

</body>
</html>