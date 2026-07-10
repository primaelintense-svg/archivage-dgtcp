<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
</head>
<body>
    <h1>Modifier {{ $user->prenom }} {{ $user->nom }}</h1>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Nom</label>
            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required>
        </div>

        <div>
            <label>Prénom</label>
            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password">
        </div>

        <div>
            <label>Rôle</label>
            <select name="role" required>
                @foreach (['agent_comptable', 'archiviste', 'administrateur', 'visiteur'] as $role)
                    <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <p><a href="{{ route('users.index') }}">Retour à la liste</a></p>
</body>
</html>
