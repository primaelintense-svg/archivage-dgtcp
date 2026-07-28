<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documents — Archiviste - Trésor Public du Bénin</title>
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
            --danger-red: #a93226;
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

        /* Conteneurs sous forme de cartes */
        .section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }

        /* Formulaire de filtre */
        .filtre-form {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .filtre-form label {
            font-weight: 600;
            color: var(--primary-green);
            font-size: 0.95rem;
        }

        .filtre-form select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.95rem;
            background-color: #faf8f5;
            color: var(--text-main);
            transition: border-color 0.2s;
        }

        .filtre-form select:focus {
            outline: none;
            border-color: var(--primary-green);
            background-color: #ffffff;
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

        /* Liens et boutons d'action */
        .btn-action {
            display: inline-block;
            padding: 6px 12px;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.85rem;
            color: var(--text-main);
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-action:hover {
            background: #e0e0e0;
            text-decoration: none;
        }

        a {
            color: var(--primary-green);
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }

        /* Messages de notification */
        .message-succes { 
            background: #e8f8f5;
            color: var(--success-green);
            border: 1px solid #a3e4d7;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px; 
        }

        .message-erreur { 
            background: #fdebd0;
            color: var(--danger-red);
            border: 1px solid #f5b041;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px; 
        }

        button[type="submit"] {
            cursor: pointer;
            padding: 8px 14px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
        }

        button[type="submit"]:hover {
            background: var(--primary-green-hover);
        }
    </style>
</head>
<body>

@include('partials.nav')

    <h1>Tous les documents</h1>

    @if (session('succes'))
        <div class="message-succes">{{ session('succes') }}</div>
    @endif
    @if ($errors->any())
        <div class="message-erreur">{{ $errors->first() }}</div>
    @endif

    <div class="section">
        <form method="GET" action="{{ route('archiviste.index') }}" class="filtre-form">
            <label>Filtrer par statut :</label>
            <select name="statut" onchange="this.form.submit()">
                <option value="">-- Tous --</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="valide" {{ request('statut') === 'valide' ? 'selected' : '' }}>Validé (à classer)</option>
                <option value="rejete" {{ request('statut') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                <option value="archive" {{ request('statut') === 'archive' ? 'selected' : '' }}>Archivé</option>
            </select>
            <noscript><button type="submit">Filtrer</button></noscript>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Déposé par</th>
                    <th>Déposé le</th>
                    <th>Classification</th>
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
                        <td>{{ $document->agentDepot->prenom ?? '' }} {{ $document->agentDepot->nom ?? '' }}</td>
                        <td>{{ $document->date_depot->format('d/m/Y') }}</td>
                        <td>{{ $document->classification?->libelle ?? '—' }}</td>
                        <td>
                            <a href="{{ route('archiviste.show', $document) }}" class="btn-action">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 20px;">Aucun document pour ce filtre.</td>
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