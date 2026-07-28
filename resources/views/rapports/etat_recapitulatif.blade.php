<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 18px; text-align: center; }
        h2 { font-size: 14px; background: #eee; padding: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background: #f2f2f2; }
        .total { font-weight: bold; text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>État récapitulatif des documents archivés</h1>
    <p>Édité le {{ now()->format('d/m/Y à H:i') }} — DGTCP, Système d'archivage numérique</p>

    @foreach ($documents as $service => $documentsDuService)
        <h2>Service : {{ $service }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Titre</th>
                    <th>Exercice</th>
                    <th>Classification</th>
                    <th>Montant</th>
                    <th>Date d'archivage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documentsDuService as $document)
                    <tr>
                        <td>{{ $document->reference }}</td>
                        <td>{{ $document->titre }}</td>
                        <td>{{ $document->exercice_comptable }}</td>
                        <td>{{ $document->classification?->libelle ?? '—' }}</td>
                        <td>{{ $document->montant ? number_format($document->montant, 2, ',', ' ') : '—' }}</td>
                        <td>{{ $document->date_traitement?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <p class="total">
        Total général : {{ $totalGeneral }} document(s) archivé(s)
        — Montant cumulé : {{ number_format($montantGeneral, 2, ',', ' ') }}
    </p>
</body>
</html>
