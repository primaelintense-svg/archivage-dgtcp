<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recherche de documents - Trésor Public du Bénin</title>
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

        /* Conteneurs sous forme de cartes */
        .section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }

        /* Bloc d'aide à la recherche */
        .aide-box {
            background: #faf8f5;
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--accent-gold);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 6px;
        }

        .aide-box strong {
            color: var(--primary-green);
            display: block;
            margin-bottom: 8px;
            font-size: 1.05rem;
        }

        .aide-box ul {
            margin: 0;
            padding-left: 20px;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .aide-box li {
            margin-bottom: 5px;
        }

        /* Formulaire de recherche en grille */
        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            color: var(--primary-green);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="date"],
        select {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.95rem;
            background-color: #faf8f5;
            color: var(--text-main);
            transition: border-color 0.2s, background-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-green);
            background-color: #ffffff;
        }

        .periode-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .periode-container input {
            flex: 1;
        }

        .periode-container span {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* Boutons du formulaire */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }

        button[type="submit"] {
            cursor: pointer;
            padding: 10px 20px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        button[type="submit"]:hover {
            background: var(--primary-green-hover);
        }

        .btn-reset {
            color: var(--text-muted);
            font-size: 0.95rem;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-reset:hover {
            color: var(--primary-green);
            text-decoration: underline;
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
    </style>
</head>
<body>

    @include('partials.nav')

    <h1>Recherche de documents archivés</h1>

    <div class="aide-box">
        <strong>Aide à la recherche</strong>
        <ul>
            <li><strong>Type, Service, Exercice</strong> : choisis dans la liste, tu ne peux pas te tromper de saisie.</li>
            <li><strong>Référence</strong> : tape juste une partie, ex. <code>0347</code> ou <code>2025</code>.</li>
            <li><strong>Mots-clés</strong> : cherche parmi les mots-clés ajoutés par l'archiviste lors de l'indexation.</li>
            <li>Remplis <strong>un seul champ suffit</strong> — pas besoin de tout remplir.</li>
        </ul>
    </div>

    <div class="section">
        <form method="GET" action="{{ route('recherche.index') }}">
            <div class="search-grid">
                <div class="form-group">
                    <label>Type de document</label>
                    <select name="type_document">
                        <option value="">-- Tous --</option>
                        @foreach ($typesDisponibles as $type)
                            <option value="{{ $type }}" {{ request('type_document') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Service</label>
                    <select name="service">
                        <option value="">-- Tous --</option>
                        @foreach ($servicesDisponibles as $service)
                            <option value="{{ $service }}" {{ request('service') === $service ? 'selected' : '' }}>
                                {{ $service }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Exercice comptable</label>
                    <select name="exercice_comptable">
                        <option value="">-- Tous --</option>
                        @foreach ($exercicesDisponibles as $exercice)
                            <option value="{{ $exercice }}" {{ request('exercice_comptable') === $exercice ? 'selected' : '' }}>
                                {{ $exercice }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Référence (partielle)</label>
                    <input type="text" name="reference" value="{{ request('reference') }}" placeholder="ex. 0347">
                </div>

                <div class="form-group form-group-full">
                    <label>Période de dépôt</label>
                    <div class="periode-container">
                        <input type="date" name="periode_debut" value="{{ request('periode_debut') }}">
                        <span>au</span>
                        <input type="date" name="periode_fin" value="{{ request('periode_fin') }}">
                    </div>
                </div>

                <div class="form-group form-group-full">
                    <label>Mots-clés</label>
                    <input type="text" name="mots_cles" value="{{ request('mots_cles') }}" placeholder="ex. mandat, paiement">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Rechercher</button>
                <a href="{{ route('recherche.index') }}" class="btn-reset">Réinitialiser</a>
            </div>
        </form>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Exercice</th>
                    <th>Classification</th>
                    <th>Déposé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr>
                        <td><strong>{{ $document->reference }}</strong></td>
                        <td>{{ $document->titre }}</td>
                        <td>{{ $document->type_document }}</td>
                        <td>{{ $document->exercice_comptable }}</td>
                        <td>{{ $document->classification?->libelle ?? '—' }}</td>
                        <td>{{ $document->date_depot->format('d/m/Y') }}</td>
                        <td><a href="{{ route('recherche.show', $document) }}" class="btn-action">Consulter</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">Aucun document ne correspond à ces critères.</td>
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