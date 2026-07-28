<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Documents à classer</title>
</head>
<body>
    @include('partials.nav')
    <h1>Documents validés en attente de classement</h1>

    @if (session('succes'))
        <div style="color: green;">{{ session('succes') }}</div>
    @endif
    @if ($errors->any())
        <div style="color: red;">{{ $errors->first() }}</div>
    @endif

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Titre</th>
                <th>Type</th>
                <th>Validé le</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $document)
                <tr>
                    <td>{{ $document->reference }}</td>
                    <td>{{ $document->titre }}</td>
                    <td>{{ $document->type_document }}</td>
                    <td>{{ $document->date_traitement?->format('d/m/Y') }}</td>
                    <td><a href="{{ route('archiviste.classerForm', $document) }}">Classer</a></td>
                </tr>
            @empty
                <tr><td colspan="5">Aucun document en attente de classement.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $documents->links() }}

    <p><a href="{{ route('archiviste.index') }}">Documents en attente de validation</a></p>
</body>
</html>
