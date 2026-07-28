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

        /* Conteneur sous forme de carte */
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
            margin-bottom: 25px;
        }

        .detail-item {
            font-size: 0.95rem;
        }

        .detail-item strong {
            color: var(--primary-green);
            display: inline-block;
            width: 210px;
        }

        .description-box {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .description-box strong {
            display: block;
            color: var(--primary-green);
            margin-bottom: 8px;
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
                <strong>Service :</strong> {{ $document->service }}
            </div>
            <div class="detail-item">
                <strong>Exercice comptable :</strong> {{ $document->exercice_comptable }}
            </div>
            <div class="detail-item">
                <strong>Montant :</strong> {{ $document->montant ? number_format($document->montant, 0, ',', ' ') . ' XOF' : '—' }}
            </div>
            <div class="detail-item">
                <strong>Déposé par :</strong> {{ $document->agentDepot->prenom ?? '' }} {{ $document->agentDepot->nom ?? '' }}
            </div>
            <div class="detail-item">
                <strong>Déposé le :</strong> {{ $document->date_depot ? $document->date_depot->format('d/m/Y H:i') : '—' }}
            </div>
            <div class="detail-item">
                <strong>Traité par :</strong> {{ $document->archivisteTraitant ? ($document->archivisteTraitant->prenom . ' ' . $document->archivisteTraitant->nom) : '— pas encore traité —' }}
            </div>
            <div class="detail-item">
                <strong>Classification :</strong> {{ $document->classification?->libelle ?? '— pas encore classé —' }}
            </div>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <strong>Date d'expiration :</strong> {{ $document->date_expiration ? $document->date_expiration->format('d/m/Y') : '—' }}
            </div>
        </div>

        @if ($document->description)
            <div class="description-box">
                <strong>Description</strong>
                <p style="margin: 0; color: var(--text-muted);">{{ $document->description }}</p>
            </div>
        @endif

        @if ($document->fichier)
            <div class="file-box">
                <div>
                    <strong>Fichier joint :</strong> {{ $document->fichier->nom }} 
                    <span style="color: var(--text-muted); font-size: 0.85rem;">({{ round($document->fichier->taille / 1024) }} Ko)</span>
                </div>
                <!-- Vous pouvez ajouter un lien de téléchargement ici si disponible ex: route('documents.download', $document) -->
            </div>
        @endif
    </div>

    <a href="{{ route('documents.index') }}" class="back-link">&larr; Retour à la liste</a>

</body>
</html>