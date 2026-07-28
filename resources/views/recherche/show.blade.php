<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $document->reference }}</title>
</head>
<body>
    @include('partials.nav')
    <h1>{{ $document->titre }}</h1>
    <p><strong>Référence :</strong> {{ $document->reference }}</p>
    <p><strong>Type :</strong> {{ $document->type_document }}</p>
    <p><strong>Service :</strong> {{ $document->service }}</p>
    <p><strong>Exercice comptable :</strong> {{ $document->exercice_comptable }}</p>
    <p><strong>Montant :</strong> {{ $document->montant ?? '—' }}</p>
    <p><strong>Description :</strong> {{ $document->description ?? '—' }}</p>
    <p><strong>Classification :</strong> {{ $document->classification?->libelle ?? '—' }}</p>
    <p><strong>Déposé le :</strong> {{ $document->date_depot->format('d/m/Y H:i') }}</p>

    @if ($document->indexations->isNotEmpty())
        <p><strong>Mots-clés :</strong>
            {{ $document->indexations->pluck('mots_cles')->filter()->implode(' ; ') }}
        </p>
    @endif

    @if ($document->fichier)
        <p>
            <a href="{{ route('recherche.telecharger', $document) }}">
                Télécharger le fichier ({{ $document->fichier->nom }})
            </a>
        </p>
    @endif

    <p><a href="{{ route('recherche.index') }}">Retour à la recherche</a></p>
</body>
</html>
