<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Archivage DGTCP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,600;8..60,700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --parchment: #F6F3EC;
            --surface: #FFFFFF;
            --ink: #23241F;
            --ink-soft: #5B5A52;
            --green: #163B2E;
            --green-deep: #0E2921;
            --gold: #A9812E;
            --gold-soft: #D8C48C;
            --line: #DCD5C2;
            --error: #7A2E2E;
            --error-bg: #F5E9E7;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background-color: var(--parchment);
            background-image: repeating-linear-gradient(
                to bottom,
                rgba(35, 36, 31, 0.035),
                rgba(35, 36, 31, 0.035) 1px,
                transparent 1px,
                transparent 34px
            );
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .liseret {
            width: 100%;
            height: 5px;
            display: flex;
        }
        .liseret span { flex: 1; }
        .liseret .vert  { background: var(--green); }
        .liseret .jaune { background: #A13A2E; }
        .liseret .rouge { background: #A13A2E; }

        .page {
            width: 100%;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .carte {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(20, 20, 15, 0.04), 0 12px 32px -16px rgba(20, 20, 15, 0.18);
            padding: 36px 36px 32px;
            animation: apparition 0.4s ease-out;
        }

        @keyframes apparition {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (prefers-reduced-motion: reduce) {
            .carte { animation: none; }
        }

        .entete {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 22px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--line);
        }

        .entete img {
            width: 52px;
            height: 52px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .entete-texte {
            text-align: center;
        }

        h1 {
            font-family: 'Source Serif 4', serif;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 4px;
            color: var(--green-deep);
            line-height: 1.2;
        }

        .sous-titre {
            font-size: 11px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--gold);
            margin: 0;
            font-weight: 500;
        }

        .erreur {
            background: var(--error-bg);
            border-left: 3px solid var(--error);
            color: var(--error);
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .champ {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--ink-soft);
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            font-family: 'Inter', sans-serif;
            /* 16px minimum : évite le zoom automatique agaçant sur iPhone/Safari */
            font-size: 16px;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: var(--parchment);
            color: var(--ink);
            transition: border-color 0.15s ease, background 0.15s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: 2px solid var(--gold);
            outline-offset: 1px;
            border-color: var(--gold);
            background: var(--surface);
        }

        .souvenir {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--ink-soft);
            margin-bottom: 24px;
        }

        .souvenir input { accent-color: var(--green); }

        button {
            width: 100%;
            background: var(--green);
            color: #F6F3EC;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            transition: background 0.15s ease;
            /* Zone tactile confortable sur mobile (min. recommandé : 44px) */
            min-height: 44px;
        }

        button:hover { background: var(--green-deep); }
        button:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; }

        .pied {
            text-align: center;
            font-size: 11px;
            color: var(--ink-soft);
            margin-top: 28px;
            letter-spacing: 0.02em;
        }

        /* ---- Tablette / petit mobile ---- */
        @media (max-width: 420px) {
            .entete { gap: 10px; }
            .entete img { width: 40px; height: 40px; }
            h1 { font-size: 17px; }
        }

        /* ---- Très petits écrans (vieux téléphones, mode paysage bas) ---- */
        @media (max-width: 360px) {
            .page { padding: 24px 12px; }

            .carte {
                padding: 24px 20px 20px;
                border-radius: 4px;
                box-shadow: none;
                border: 1px solid var(--line);
            }

            .entete {
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 16px;
                padding-bottom: 16px;
            }

            .entete img { width: 34px; height: 34px; }
            h1 { font-size: 15px; }
            .sous-titre { font-size: 10px; }
        }

        /* ---- Écran très bas (mode paysage sur mobile) ---- */
        @media (max-height: 480px) and (orientation: landscape) {
            .page { padding: 16px 20px; align-items: flex-start; }
            .carte { padding: 20px 28px; }
            .entete { margin-bottom: 12px; padding-bottom: 12px; }
        }
    </style>
</head>
<body>

<div class="liseret">
    <span class="vert"></span><span class="jaune"></span><span class="rouge"></span>
</div>

<div class="page">
    <div class="carte">
        <div class="entete">
            <img src="{{ asset('images/ministere.png') }}" alt="Armoiries de la République du Bénin">
            <div class="entete-texte">
                <h1>Archivage numérique</h1>
                <p class="sous-titre">Trésor Public du Bénin</p>
            </div>
            <img src="{{ asset('images/tresor.png') }}" alt="Trésor Public">
        </div>

        @if ($errors->any())
            <div class="erreur">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="champ">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="champ">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <label class="souvenir">
                <input type="checkbox" name="remember">
                Se souvenir de moi
            </label>

            <button type="submit">Se connecter</button>
        </form>

        <p style="text-align: center; font-size: 13px; margin-top: 20px;">
            Pas encore de compte ?
            <a href="{{ route('demandeCompte.create') }}" style="color: var(--green);">Demander un accès</a>
        </p>

        <p class="pied">Direction Générale du Trésor et de la Comptabilité Publique</p>
    </div>
</div>

</body>
</html>