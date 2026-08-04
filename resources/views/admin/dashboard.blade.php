<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord Administrateur - Trésor Public du Bénin</title>
    <style>
        :root {
            --bg-color: #fcfbfa;
            --card-bg: #ffffff;
            --primary-green: #113f2d;
            --primary-green-hover: #0b2b1e;
            --accent-gold: #c5a880;
            --text-main: #2b2b2b;
            --text-muted: #666666;
            --border-color: #e3dec3;
            --danger-red: #a93226;
            --success-green: #1e8449;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-main);
            background: var(--bg-color);
            margin: 0;
            padding: 30px;
            line-height: 1.6;
        }

        .barre-nav {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-left: 5px solid var(--primary-green);
            padding: 15px 25px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border-radius: 4px;
        }

        .barre-nav span { font-size: 1rem; color: var(--text-main); }

        h1 { color: var(--primary-green); font-size: 1.8rem; margin-bottom: 25px; font-weight: 700; }

        h2 {
            font-size: 1.2rem;
            color: var(--primary-green);
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 8px;
            font-weight: 600;
        }

        .section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 0.95rem; }
        th, td { border: 1px solid #eee; padding: 12px 15px; text-align: left; }
        th { background: var(--primary-green); color: #ffffff; font-weight: 600; }
        tr:nth-child(even) { background-color: #faf8f5; }

        /* ---- Enveloppe de défilement horizontal pour tous les tableaux ---- */
        .tableau-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 4px;
        }
        .tableau-scroll table { min-width: 560px; }
        .tableau-scroll th, .tableau-scroll td { white-space: nowrap; }

        .grille-stats { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-carte {
            background: #faf8f5;
            border: 1px solid var(--border-color);
            border-top: 4px solid var(--primary-green);
            padding: 20px;
            flex: 1;
            min-width: 150px;
            border-radius: 4px;
            text-align: center;
        }
        .stat-carte strong { font-size: 2rem; color: var(--primary-green); display: block; margin-bottom: 5px; }

        .graphiques-flex { display: flex; gap: 30px; flex-wrap: wrap; }
        .graphique-bloc { flex: 1; min-width: 240px; }
        .graphique-bloc h3 {
            font-size: 0.95rem;
            color: var(--text-muted);
            font-weight: 600;
            margin: 0 0 10px;
        }

        .barre-fond { background: #eee; border-radius: 4px; height: 12px; width: 100%; margin-bottom: 15px; overflow: hidden; }
        .barre-remplie { height: 100%; background: var(--primary-green); border-radius: 4px; }
        .barre-etiquette { display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 5px; }

        button {
            cursor: pointer;
            padding: 8px 14px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            transition: background 0.2s;
            /* Zone tactile confortable */
            min-height: 40px;
        }
        button:hover { background: var(--primary-green-hover); }
        .btn-danger { background: var(--danger-red); }
        .btn-danger:hover { background: #8b281f; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }

        a { color: var(--primary-green); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .action-link-ajout { display: inline-block; margin-bottom: 15px; font-weight: 600; }

        .liens-rapports a {
            display: inline-block;
            margin-right: 24px;
            padding: 8px 14px;
            background: #faf8f5;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-weight: 600;
        }
        .liens-rapports a:hover { background: #f0ede5; text-decoration: none; }

        .message-succes {
            background: #e8f8f5; color: var(--success-green); border: 1px solid #a3e4d7;
            padding: 12px 15px; border-radius: 4px; margin-bottom: 20px;
        }
        .message-erreur {
            background: #fdebd0; color: var(--danger-red); border: 1px solid #f5b041;
            padding: 12px 15px; border-radius: 4px; margin-bottom: 20px;
        }
        .message-notification {
            background: #eaf2f8; color: var(--primary-green); border: 1px solid #aed6f1;
            padding: 12px 15px; border-radius: 4px; margin-bottom: 20px;
        }

        .actif { color: var(--success-green); font-weight: bold; }
        .desactive { color: var(--text-muted); font-weight: bold; }

        .actions-cell { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .bouton-mini {
            padding: 6px 10px; background: #f0f0f0; border-radius: 4px;
            border: 1px solid #ccc; font-size: 0.85rem; color: var(--text-main);
        }

        form.filtre {
            display: flex; flex-wrap: wrap; gap: 14px; align-items: end; margin-bottom: 18px;
        }
        form.filtre label { display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 4px; }
        form.filtre select, form.filtre input[type="date"] {
            font-family: inherit; font-size: 0.9rem; padding: 7px 9px;
            border: 1px solid var(--border-color); border-radius: 4px; background: #faf8f5;
        }
        form.filtre a { font-size: 0.85rem; align-self: center; color: var(--text-muted); }

        .pagination-zone { margin-top: 12px; font-size: 0.85rem; }

        /* ============================================================
           RESPONSIVE — Tablette (≤ 768px)
           ============================================================ */
        @media (max-width: 768px) {
            body { padding: 16px; }

            .section { padding: 16px; margin-bottom: 18px; }

            h1 { font-size: 1.4rem; }
            h2 { font-size: 1.05rem; }

            .barre-nav {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
                padding: 14px 16px;
            }

            .barre-nav form { width: 100%; }
            .barre-nav button { width: 100%; }

            .graphiques-flex { gap: 20px; }
            .graphique-bloc { min-width: 100%; }

            form.filtre {
                flex-direction: column;
                align-items: stretch;
            }
            form.filtre > div { width: 100%; }
            form.filtre select,
            form.filtre input[type="date"] {
                width: 100%;
            }
            form.filtre button { width: 100%; }
            form.filtre a { text-align: center; }
        }

        /* ============================================================
           RESPONSIVE — Mobile (≤ 480px)
           ============================================================ */
        @media (max-width: 480px) {
            body { padding: 10px; }

            .section { padding: 12px; border-radius: 4px; }

            .stat-carte { min-width: 130px; padding: 14px; }
            .stat-carte strong { font-size: 1.5rem; }

            table { font-size: 0.85rem; }
            th, td { padding: 8px 10px; }

            .actions-cell { flex-direction: column; align-items: stretch; }
            .actions-cell form,
            .actions-cell a,
            .actions-cell button { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

<div class="barre-nav">
    <span><strong>Administration - Archivage Numérique</strong> | {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="btn-secondary">Se déconnecter</button>
    </form>
</div>

<h1>Tableau de bord</h1>

@if (session('succes'))
    <div class="message-succes">{{ session('succes') }}</div>
@endif
@if ($errors->any())
    <div class="message-erreur">{{ $errors->first() }}</div>
@endif

{{-- ============ STATISTIQUES ============ --}}
<div class="section">
    <h2>Statistiques Générales</h2>
    <div class="grille-stats">
        <div class="stat-carte"><strong>{{ $stats['total_documents'] }}</strong>Total Documents</div>
        <div class="stat-carte"><strong>{{ $stats['en_attente'] }}</strong>En attente</div>
        <div class="stat-carte"><strong>{{ $stats['valides'] }}</strong>À classer</div>
        <div class="stat-carte"><strong>{{ $stats['rejetes'] }}</strong>Rejetés</div>
        <div class="stat-carte"><strong>{{ $stats['archives'] }}</strong>Archivés</div>
        <div class="stat-carte"><strong>{{ $stats['utilisateurs_actifs'] }}</strong>Utilisateurs actifs</div>
    </div>
</div>

{{-- ============ GRAPHIQUES ============ --}}
<div class="section">
    <h2>Répartition des documents</h2>
    <div class="graphiques-flex">

        <div class="graphique-bloc">
            <h3>Par état</h3>
            @foreach ($parEtat as $label => $valeur)
                @php $max = max(array_values($parEtat) ?: [1]); @endphp
                <div class="barre-etiquette"><span>{{ $label }}</span><span><strong>{{ $valeur }}</strong></span></div>
                <div class="barre-fond">
                    <div class="barre-remplie" style="width: {{ $max > 0 ? ($valeur / $max * 100) : 0 }}%;"></div>
                </div>
            @endforeach
        </div>

        <div class="graphique-bloc">
            <h3>Par type de document</h3>
            @php $maxType = $parType->max() ?: 1; @endphp
            @forelse ($parType as $label => $valeur)
                <div class="barre-etiquette"><span>{{ $label }}</span><span><strong>{{ $valeur }}</strong></span></div>
                <div class="barre-fond">
                    <div class="barre-remplie" style="width: {{ $valeur / $maxType * 100 }}%;"></div>
                </div>
            @empty
                <p style="color: var(--text-muted); font-size: 0.9rem;">Aucune donnée disponible.</p>
            @endforelse
        </div>

        <div class="graphique-bloc">
            <h3>Par service émetteur</h3>
            @php $maxService = $parService->max() ?: 1; @endphp
            @forelse ($parService as $label => $valeur)
                <div class="barre-etiquette"><span>{{ $label }}</span><span><strong>{{ $valeur }}</strong></span></div>
                <div class="barre-fond">
                    <div class="barre-remplie" style="width: {{ $valeur / $maxService * 100 }}%;"></div>
                </div>
            @empty
                <p style="color: var(--text-muted); font-size: 0.9rem;">Aucune donnée disponible.</p>
            @endforelse
        </div>

    </div>
</div>

{{-- ============ RAPPORTS PDF ============ --}}
<div class="section">
    <h2>Rapports PDF</h2>
    <div class="tableau-scroll">
        <table>
            <thead>
                <tr>
                    <th>Rapport</th>
                    <th>Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>État récapitulatif</td>
                    <td>Documents archivés, groupés par service et par exercice comptable</td>
                    <td><a href="{{ route('rapports.etatRecapitulatif') }}">📄 Télécharger</a></td>
                </tr>
                <tr>
                    <td>Documents expirant</td>
                    <td>Documents arrivant à expiration de conservation (90 jours ou moins)</td>
                    <td><a href="{{ route('rapports.documentsExpirant') }}">📄 Télécharger</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ============ DEMANDES D'ACCÈS ============ --}}
@if ($demandesCompteEnAttente > 0)
    <div class="section">
        <h2>🔔 Demandes d'accès</h2>
        <div class="message-notification">
            <strong>{{ $demandesCompteEnAttente }}</strong> nouvelle(s) demande(s) d'accès en attente de traitement.
            <a href="{{ route('demandeCompte.index') }}">Voir les demandes</a>
        </div>
    </div>
@endif

{{-- ============ ALERTES D'EXPIRATION ============ --}}
<div class="section">
    <h2>Alertes d'expiration</h2>
    <div class="tableau-scroll">
        <table>
            <thead>
                <tr><th>Référence</th><th>Titre</th><th>Déposé par</th><th>Expiration</th></tr>
            </thead>
            <tbody>
                @forelse ($documentsExpirantBientot as $document)
                    <tr>
                        <td>{{ $document->reference }}</td>
                        <td>{{ $document->titre }}</td>
                        <td>{{ $document->agentDepot->prenom }} {{ $document->agentDepot->nom }}</td>
                        <td>{{ $document->date_expiration->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">Aucun document n'expire prochainement.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ============ JOURNAL DES ACTIVITÉS ============ --}}
<div class="section">
    <h2>Journal des activités</h2>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="filtre">
        <input type="hidden" name="page_users" value="{{ request('page_users') }}">
        <div>
            <label>Utilisateur</label>
            <select name="utilisateur_id">
                <option value="">-- Tous --</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ (string) request('utilisateur_id') === (string) $u->id ? 'selected' : '' }}>
                        {{ $u->prenom }} {{ $u->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Action</label>
            <select name="action">
                <option value="">-- Toutes --</option>
                @foreach ($actionsDisponibles as $action)
                    <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Du</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}">
        </div>
        <div>
            <label>Au</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}">
        </div>
        <button type="submit">Filtrer</button>
        <a href="{{ route('admin.dashboard') }}">Réinitialiser</a>
    </form>

    <div class="tableau-scroll">
        <table>
            <thead>
                <tr><th>Date</th><th>Utilisateur</th><th>Action</th><th>Détails</th></tr>
            </thead>
            <tbody>
                @forelse ($entrees as $entree)
                    <tr>
                        <td>{{ $entree->date_action->format('d/m/Y H:i') }}</td>
                        <td>{{ $entree->utilisateur ? $entree->utilisateur->prenom . ' ' . $entree->utilisateur->nom : '— (non authentifié)' }}</td>
                        <td>{{ $entree->action }}</td>
                        <td>{{ $entree->details }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Aucune activité pour ces critères.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ============ GESTION DES UTILISATEURS ============ --}}
<div class="section">
    <h2>Gestion des Utilisateurs</h2>

    <a href="{{ route('users.create') }}" class="action-link-ajout">+ Ajouter un utilisateur</a>

    <div class="tableau-scroll">
        <table>
            <thead>
                <tr>
                    <th>Nom &amp; Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->nom }} {{ $user->prenom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td class="{{ $user->actif ? 'actif' : 'desactive' }}">
                            {{ $user->actif ? 'Actif' : 'Désactivé' }}
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('users.edit', $user) }}" class="bouton-mini">Modifier</a>

                                <form action="{{ route('users.toggleActif', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-secondary" style="padding: 6px 10px; font-size: 0.85rem;">
                                        {{ $user->actif ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>

                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Supprimer définitivement {{ $user->email }} ? Cette action est irréversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="padding: 6px 10px; font-size: 0.85rem;">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-zone">{{ $users->links() }}</div>
</div>

</body>
</html>