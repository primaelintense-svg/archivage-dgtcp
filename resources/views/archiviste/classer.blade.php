<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Classer {{ $document->reference }}</title>
</head>
<body>
    @include('partials.nav')
    <h1>Classer le document {{ $document->reference }}</h1>
    <p><strong>Titre :</strong> {{ $document->titre }}</p>
    <p><strong>Type :</strong> {{ $document->type_document }}</p>

    @if ($errors->any())
        <div style="color: red;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('archiviste.classer', $document) }}">
        @csrf

        <label>Classification (emplacement numérique)</label>
        <select name="classification_id" required>
            <option value="">-- Choisir --</option>
            @foreach ($classifications as $classification)
                <option value="{{ $classification->id }}">
                    {{ $classification->code }} — {{ $classification->libelle }}
                </option>
            @endforeach
        </select>

        <button type="submit">Classer et archiver le document</button>
    </form>

    <p><a href="{{ route('archiviste.aClasser') }}">Retour à la liste</a></p>
</body>
</html>
