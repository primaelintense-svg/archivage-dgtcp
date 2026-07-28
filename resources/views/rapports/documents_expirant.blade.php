<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 18px; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background: #f2f2f2; }
        .urgent { background: #fdd; }
    </style>
</head>
<body>
    <h1>Documents arrivant à expiration (90 jours ou moins)</h1>
    <p>Édité le {{ now()->format('d/m/Y à H:i') }} — DGTCP, Système d'archivage numérique</p>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Titre</th>
                <th>Déposé par</th>
                <th>Date d'expiration</th>
                <th>Jours restants</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $document)
                <tr class="{{ now()->diffInDays($document->date_expiration, false) <= 0 ? 'urgent' : '' }}">
                    <td>{{ $document->reference }}</td>
                    <td>{{ $document->titre }}</td>
                    <td>{{ $document->agentDepot->prenom }} {{ $document->agentDepot->nom }}</td>
                    <td>{{ $document->date_expiration->format('d/m/Y') }}</td>
                    <td>{{ (int) now()->diffInDays($document->date_expiration, false) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Aucun document n'arrive à expiration prochainement.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
