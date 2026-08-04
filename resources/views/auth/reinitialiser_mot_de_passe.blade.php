<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réinitialiser le mot de passe — Archivage DGTCP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,opsz,wght@0,8..60,600;1,8..60,500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --parchment: #F6F3EC; --surface: #FFFFFF; --ink: #23241F; --ink-soft: #5B5A52;
            --green: #163B2E; --green-deep: #0E2921; --gold: #A9812E;
            --line: #DCD5C2; --error: #7A2E2E; --error-bg: #F5E9E7;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; font-family: 'Inter', sans-serif; color: var(--ink);
            background-color: var(--parchment);
            background-image: repeating-linear-gradient(to bottom, rgba(35,36,31,0.035), rgba(35,36,31,0.035) 1px, transparent 1px, transparent 34px);
            display: flex; flex-direction: column; align-items: center;
        }
        .liseret { width: 100%; height: 5px; display: flex; }
        .liseret span { flex: 1; }
        .liseret .vert { background: var(--green); }
        .liseret .jaune { background: #E7B547; }
        .liseret .rouge { background: #A13A2E; }
        .page { width: 100%; flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .carte {
            width: 100%; max-width: 400px; background: var(--surface); border: 1px solid var(--line);
            border-radius: 6px; box-shadow: 0 1px 2px rgba(20,20,15,0.04), 0 12px 32px -16px rgba(20,20,15,0.18);
            padding: 36px;
        }
        h1 { font-family: 'Source Serif 4', serif; font-style: italic; font-weight: 600; font-size: 19px; text-align: center; margin: 0 0 24px; color: var(--green-deep); }
        .erreur { background: var(--error-bg); border-left: 3px solid var(--error); color: var(--error); font-size: 13px; padding: 10px 12px; border-radius: 3px; margin-bottom: 20px; }
        .champ { margin-bottom: 18px; }
        label { display: block; font-size: 12px; font-weight: 500; color: var(--ink-soft); margin-bottom: 6px; }
        input { width: 100%; font-family: 'Inter', sans-serif; font-size: 14px; padding: 10px 12px; border: 1px solid var(--line); border-radius: 4px; background: var(--parchment); color: var(--ink); }
        input:focus { outline: 2px solid var(--gold); outline-offset: 1px; border-color: var(--gold); background: var(--surface); }
        button { width: 100%; background: var(--green); color: #F6F3EC; border: none; border-radius: 4px; padding: 12px; font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 4px; }
        button:hover { background: var(--green-deep); }
    </style>
</head>
<body>

<div class="liseret"><span class="vert"></span><span class="jaune"></span><span class="rouge"></span></div>

<div class="page">
    <div class="carte">
        <h1>Choisir un nouveau mot de passe</h1>

        @if ($errors->any())
            <div class="erreur">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="champ">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
            </div>

            <div class="champ">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" minlength="8" required>
            </div>

            <div class="champ">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required>
            </div>

            <button type="submit">Réinitialiser le mot de passe</button>
        </form>
    </div>
</div>

</body>
</html>
