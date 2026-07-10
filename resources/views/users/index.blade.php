<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
</head>
<body>
    <h1>Gestion des utilisateurs</h1>

    @if (session('succes'))
        <div style="color: green;">{{ session('succes') }}</div>
    @endif
    @if ($errors->any())
        <div style="color: red;">{{ $errors->first() }}</div>
    @endif

    <p><a href="{{ route('users.create') }}">+ Ajouter un utilisateur</a></p>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->nom }}</td>
                    <td>{{ $user->prenom }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->actif ? 'Actif' : 'Désactivé' }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}">Modifier</a>
                        &nbsp;|&nbsp;
                        <form action="{{ route('users.toggleActif', $user) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit">
                                {{ $user->actif ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</body>
</html>
