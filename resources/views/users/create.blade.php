<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
</head>
<body>
    <h1>Ajouter un utilisateur</h1>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div>
            <label>Nom</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required>
        </div>

        <div>
            <label>Prénom</label>
            <input type="text" name="prenom" value="{{ old('prenom') }}" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Rôle</label>
            <select name="role" required>
                <option value="agent_comptable" {{ old('role') === 'agent_comptable' ? 'selected' : '' }}>Agent comptable</option>
                <option value="archiviste" {{ old('role') === 'archiviste' ? 'selected' : '' }}>Archiviste</option>
                <option value="administrateur" {{ old('role') === 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                <option value="visiteur" {{ old('role') === 'visiteur' ? 'selected' : '' }}>Visiteur</option>
            </select>
        </div>

        <button type="submit">Créer le compte</button>
    </form>

    <p><a href="{{ route('users.index') }}">Retour à la liste</a></p>
</body>
</html>
