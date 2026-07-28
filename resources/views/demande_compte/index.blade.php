<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demandes d'accès en attente - Trésor Public du Bénin</title>
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
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }

        /* Tableaux modernisés */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        th, td {
            border: 1px solid #eee;
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
        }

        th { 
            background: var(--primary-green); 
            color: #ffffff;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #faf8f5;
        }

        /* Formulaires d'actions dans le tableau */
        .actions-cell {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .form-action-inline {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            margin: 0;
        }

        input[type="text"] {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.85rem;
            background-color: #faf8f5;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-green);
            background-color: #ffffff;
        }

        /* Boutons */
        button {
            cursor: pointer;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: background 0.2s;
            color: white;
        }

        .btn-success {
            background: var(--success-green);
        }
        .btn-success:hover {
            background: #196f3d;
        }

        .btn-danger {
            background: var(--danger-red);
        }
        .btn-danger:hover {
            background: #8b281f;
        }

        /* Messages de notification */
        .message-succes { 
            background: #e8f8f5;
            color: var(--success-green);
            border: 1px solid #a3e4d7;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px; 
        }

        .message-erreur { 
            background: #fdebd0;
            color: var(--danger-red);
            border: 1px solid #f5b041;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px; 
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

    <h1>Demandes d'accès en attente</h1>

    @if (session('succes'))
        <div class="message-succes">{{ session('succes') }}</div>
    @endif
    @if ($errors->any())
        <div class="message-erreur">{{ $errors->first() }}</div>
    @endif

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Nom & Prénom</th>
                    <th>Email</th>
                    <th>Motif</th>
                    <th>Demandé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($demandes as $demande)
                    <tr>
                        <td><strong>{{ $demande->nom }} {{ $demande->prenom }}</strong></td>
                        <td>{{ $demande->email }}</td>
                        <td>{{ $demande->motif ?? '—' }}</td>
                        <td>{{ $demande->date_demande->format('d/m/Y') }}</td>
                        <td>
                            <div class="actions-cell">
                                <!-- Bouton Approuver -->
                                <form action="{{ route('demandeCompte.approuver', $demande) }}" method="POST" class="form-action-inline">
                                    @csrf
                                    <button type="submit" class="btn-success">Approuver</button>
                                </form>

                                <!-- Formulaire Rejeter -->
                                <form action="{{ route('demandeCompte.rejeter', $demande) }}" method="POST" class="form-action-inline">
                                    @csrf
                                    <input type="text" name="motif_rejet" placeholder="Motif du rejet" required>
                                    <button type="submit" class="btn-danger">Rejeter</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">Aucune demande en attente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $demandes->links() }}
        </div>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="back-link">&larr; Retour au tableau de bord</a>

</body>
</html>