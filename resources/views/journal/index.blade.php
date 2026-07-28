<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Journal des activités</title>
</head>
<body>
    <h1>Journal des activités</h1>

    <form method="GET" action="{{ route('journal.index') }}">
        <div>
            <label>Utilisateur</label>
            <select name="utilisateur_id">
                <option value="">-- Tous --</option>
                @foreach ($utilisateurs as $utilisateur)
                    <option value="{{ $utilisateur->id }}" {{ (string) request('utilisateur_id') === (string) $utilisateur->id ? 'selected' : '' }}>
                        {{ $utilisateur->prenom }} {{ $utilisateur->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Action</label>
            <select name="action">
                <option value="">-- Toutes --</option>
                @foreach ($actionsDisponibles as $action)
                    <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                        {{ $action }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Du</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}">
            <label>au</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}">
        </div>
        <button type="submit">Filtrer</button>
        <a href="{{ route('journal.index') }}">Réinitialiser</a>
    </form>

    <hr>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Action</th>
                <th>Détails</th>
            </tr>
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
                <tr><td colspan="4">Aucune activité enregistrée pour ces critères.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $entrees->links() }}

    <p><a href="{{ route('admin.dashboard') }}">Retour au tableau de bord</a></p>
</body>
</html>
