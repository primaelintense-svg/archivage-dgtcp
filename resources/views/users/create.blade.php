<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un utilisateur - Trésor Public du Bénin</title>
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
            --danger-red: #a93226;
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
            max-width: 600px;
        }

        /* Messages d'erreur */
        .message-erreur { 
            background: #fdebd0;
            color: var(--danger-red);
            border: 1px solid #f5b041;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            max-width: 600px;
        }

        .message-erreur ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Formulaire de création */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            color: var(--primary-green);
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.95rem;
            background-color: #faf8f5;
            color: var(--text-main);
            box-sizing: border-box;
            transition: border-color 0.2s, background-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-green);
            background-color: #ffffff;
        }

        /* Bouton de soumission */
        button[type="submit"] {
            cursor: pointer;
            padding: 10px 20px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        button[type="submit"]:hover {
            background: var(--primary-green-hover);
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

    <h1>Ajouter un utilisateur</h1>

    @if ($errors->any())
        <div class="message-erreur">
            <ul>
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="section">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required>
            </div>

            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
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
    </div>

    <a href="{{ route('admin.dashboard') }}" class="back-link">&larr; Retour à la liste</a>

</body>
</html>