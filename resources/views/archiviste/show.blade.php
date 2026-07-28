<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $document->reference }} - Trésor Public du Bénin</title>
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

        h2 {
            color: var(--primary-green);
            font-size: 1.2rem;
            margin-top: 0;
            margin-bottom: 15px;
        }

        /* Conteneurs sous forme de cartes */
        .section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 30px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
            max-width: 800px;
        }

        /* Grille des détails du document */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 15px 30px;
            margin-bottom: 20px;
        }

        .detail-item {
            font-size: 0.95rem;
        }

        .detail-item strong {
            color: var(--primary-green);
            display: inline-block;
            width: 190px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        /* Fichier attaché */
        .file-box {
            margin-top: 20px;
            padding: 15px;
            background: #faf8f5;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Formulaires d'action */
        .action-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .action-box {
            background: #faf8f5;
            border: 1px solid var(--border-color);
            padding: 20px;
            border-radius: 6px;
        }

        .action-box.danger {
            border-color: #f5b041;
            background: #fef9e7;
        }

        label {
            display: block;
            font-weight: 600;
            color: var(--primary-green);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.95rem;
            background-color: #ffffff;
            color: var(--text-main);
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-green);
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        /* Boutons */
        button {
            cursor: pointer;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-primary {
            background: var(--primary-green);
        }
        .btn-primary:hover {
            background: var(--primary-green-hover);
        }

        .btn-danger {
            background: var(--danger-red);
        }
        .btn-danger:hover {
            background: #8b281f;
        }

        /* Messages de notification */
        .message-succes { 
            background: #e8f8f5;
            color: var(--success-green);
            border: 1px solid #a3e4d7;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            max-width: 800px;
        }

        .message-erreur { 
            background: #fdebd0;
            color: var(--danger-red);
            border: 1px solid #f5b041;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            max-width: 800px;
        }

        /* Liens */
        a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    @include('partials.nav')

    <h1>{{ $document->titre }}</h1>

    @if (session('succes'))
        <div class="message-succes">{{ session('succes') }}</div>
    @endif
    @if ($errors->any())
        <div class="message-erreur">{{ $errors->first() }}</div>
    @endif

    <div class="section">
        <div class="details-grid">
            <div class="detail-item">
                <strong>Référence :</strong> {{ $document->reference }}
            </div>
            <div class="detail-item">
                <strong>Statut :</strong> {{ ucfirst(str_replace('_', ' ', $document->statut)) }}
            </div>
            <div class="detail-item">
                <strong>Type :</strong> {{ $document->type_document }}
            </div>
            <div class="detail-item">
                <strong>Service émetteur :</strong> {{ $document->service }}
            </div>
            <div class="detail-item">
                <strong>Exercice comptable :</strong> {{ $document->exercice_comptable }}
            </div>
            <div class="detail-item full-width">
                <strong>Description :</strong> {{ $document->description ?? '—' }}
            </div>
            <div class="detail-item">
                <strong>Déposé par :</strong> {{ $document->agentDepot->prenom ?? '' }} {{ $document->agentDepot->nom ?? '' }}
            </div>
            <div class="detail-item">
                <strong>Déposé le :</strong> {{ $document->date_depot ? $document->date_depot->format('d/m/Y H:i') : '—' }}
            </div>

            @if ($document->archivisteTraitant)
                <div class="detail-item full-width">
                    <strong>Traité par :</strong> {{ $document->archivisteTraitant->prenom }} {{ $document->archivisteTraitant->nom }} le {{ $document->date_traitement->format('d/m/Y') }}
                </div>
            @endif

            @if ($document->statut === 'rejete' && $document->motif_rejet)
                <div class="detail-item full-width" style="color: var(--danger-red);">
                    <strong>Motif du rejet :</strong> {{ $document->motif_rejet }}
                </div>
            @endif

            @if ($document->classification)
                <div class="detail-item full-width">
                    <strong>Classification :</strong> {{ $document->classification->libelle }}
                </div>
            @endif

            @if ($document->indexations->isNotEmpty())
                <div class="detail-item full-width">
                    <strong>Mots-clés :</strong> {{ $document->indexations->pluck('mots_cles')->filter()->implode(' ; ') }}
                </div>
            @endif
        </div>

        @if ($document->fichier)
            <div class="file-box">
                <div>
                    <strong>Fichier joint :</strong> {{ $document->fichier->nom }}
                </div>
                <a href="{{ route('documents.fichier', $document) }}" target="_blank" class="btn-primary" style="padding: 6px 12px; font-size: 0.85rem; border-radius: 4px;">Ouvrir le fichier</a>
            </div>
        @endif
    </div>

    {{-- Actions disponibles selon le statut actuel --}}
    <div class="section">
        @if ($document->statut === 'en_attente')
            <div class="action-container">
                <div class="action-box">
                    <h2>Valider et indexer</h2>
                    <form method="POST" action="{{ route('archiviste.valider', $document) }}">
                        @csrf
                        <div style="margin-bottom: 10px;">
                            <label>Mots-clés d'indexation (optionnel)</label>
                            <input type="text" name="mots_cles" placeholder="ex. mandat, paiement, comptabilité">
                        </div>
                        <button type="submit" class="btn-primary">Valider le document</button>
                    </form>
                </div>

                <div class="action-box danger">
                    <h2>Rejeter</h2>
                    <form method="POST" action="{{ route('archiviste.rejeter', $document) }}">
                        @csrf
                        <div style="margin-bottom: 10px;">
                            <label>Motif du rejet (obligatoire)</label>
                            <textarea name="motif_rejet" required placeholder="Précisez la raison du rejet..."></textarea>
                        </div>
                        <button type="submit" class="btn-danger">Rejeter le document</button>
                    </form>
                </div>
            </div>

        @elseif ($document->statut === 'valide')
            <div class="action-box">
                <h2>Classer ce document</h2>
                <form method="POST" action="{{ route('archiviste.classer', $document) }}">
                    @csrf
                    <div style="margin-bottom: 10px;">
                        <label>Classification (emplacement numérique)</label>
                        <select name="classification_id" required>
                            <option value="">-- Choisir un emplacement --</option>
                            @foreach ($classifications as $classification)
                                <option value="{{ $classification->id }}">{{ $classification->code }} — {{ $classification->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">Classer et archiver</button>
                </form>
            </div>

        @else
            <p style="font-style: italic; color: var(--text-muted); margin: 0;">
                Ce document est {{ $document->statut === 'archive' ? 'déjà archivé' : 'déjà rejeté' }} — aucune action supplémentaire disponible.
            </p>
        @endif
    </div>

    <a href="{{ route('archiviste.index') }}" class="back-link">&larr; Retour à la liste</a>

</body>
</html>