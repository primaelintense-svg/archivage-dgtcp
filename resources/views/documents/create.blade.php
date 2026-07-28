<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Déposer un document - Trésor Public du Bénin</title>
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

        /* Conteneur principal sous forme de carte */
        .section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 30px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
            max-width: 800px;
        }

        /* Style des champs du formulaire */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-green);
            font-size: 0.95rem;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.95rem;
            background-color: #faf8f5;
            box-sizing: border-box;
            transition: border-color 0.2s, background-color 0.2s;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            background-color: #ffffff;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="file"] {
            padding: 8px 0;
            font-size: 0.9rem;
        }

        /* Bouton de soumission */
        .btn-primary {
            cursor: pointer;
            padding: 12px 20px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: var(--primary-green-hover);
        }

        /* Liens */
        a {
            color: var(--primary-green);
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }

        /* Messages d'erreur */
        .message-erreur { 
            background: #fdebd0;
            color: var(--danger-red);
            border: 1px solid #f5b041;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px; 
            max-width: 800px;
        }

        .message-erreur ul {
            margin: 0;
            padding-left: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    @include('partials.nav')

    <h1>Déposer un document comptable</h1>

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
        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Titre du document</label>
                <input type="text" name="titre" value="{{ old('titre') }}" placeholder="Ex: Facture d'achat de matériel" required>
            </div>
           
             <div>
            <label>Type de document</label>
            <select name="type_document" required>
                <option value="">-- Choisir --</option>
                <option value="Facture" {{ old('type_document') === 'Facture' ? 'selected' : '' }}>Facture</option>
                <option value="Bordereau de dépenses" {{ old('type_document') === 'Bordereau de dépenses' ? 'selected' : '' }}>Bordereau de dépenses</option>
                <option value="Bordereau d'émission" {{ old('type_document') === 'Bordereau d\'émission' ? 'selected' : '' }}>Bordereau d'émission</option>
                <option value="Mandat de paiement" {{ old('type_document') === 'Mandat de paiement' ? 'selected' : '' }}>Mandat de paiement</option>
                <option value="Ordre de recette" {{ old('type_document') === 'Ordre de recette' ? 'selected' : '' }}>Ordre de recette</option>
                <option value="Titre de perception" {{ old('type_document') === 'Titre de perception' ? 'selected' : '' }}>Titre de perception</option>
                <option value="Reçu" {{ old('type_document') === 'Reçu' ? 'selected' : '' }}>Reçu</option>
                <option value="Quittance" {{ old('type_document') === 'Quittance' ? 'selected' : '' }}>Quittance</option>
                <option value="Bon de commande" {{ old('type_document') === 'Bon de commande' ? 'selected' : '' }}>Bon de commande</option>
                <option value="Décompte" {{ old('type_document') === 'Décompte' ? 'selected' : '' }}>Décompte</option>
                <option value="Attestation de service fait" {{ old('type_document') === 'Attestation de service fait' ? 'selected' : '' }}>Attestation de service fait</option>
                <option value="Pièce justificative de dépense" {{ old('type_document') === 'Pièce justificative de dépense' ? 'selected' : '' }}>Pièce justificative de dépense</option>
                <option value="État de rapprochement bancaire" {{ old('type_document') === 'État de rapprochement bancaire' ? 'selected' : '' }}>État de rapprochement bancaire</option>
                <option value="Avis de virement" {{ old('type_document') === 'Avis de virement' ? 'selected' : '' }}>Avis de virement</option>
                <option value="Chèque" {{ old('type_document') === 'Chèque' ? 'selected' : '' }}>Chèque</option>
                <option value="Certificat administratif" {{ old('type_document') === 'Certificat administratif' ? 'selected' : '' }}>Certificat administratif</option>
                <option value="Autre" {{ old('type_document') === 'Autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

 <div>
    <div>
    <label>Exercice comptable</label>
    <select name="exercice_comptable" required>
        <option value="">-- Choisir --</option>
        @for ($annee = now()->year + 1; $annee >= 2000; $annee--)
            <option value="{{ $annee }}" {{ old('exercice_comptable') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
        @endfor
    </select>
</div>
</div>
        <div>
            <label>Service émetteur</label>
            <select name="service" required>
                <option value="">-- Choisir --</option>
                <option value="Direction du Budget" {{ old('service') === 'Direction du Budget' ? 'selected' : '' }}>Direction du Budget</option>
                <option value="Direction de la Comptabilité Publique" {{ old('service') === 'Direction de la Comptabilité Publique' ? 'selected' : '' }}>Direction de la Comptabilité Publique</option>
                <option value="Direction de la Solde" {{ old('service') === 'Direction de la Solde' ? 'selected' : '' }}>Direction de la Solde</option>
                <option value="Direction des Marchés Publics" {{ old('service') === 'Direction des Marchés Publics' ? 'selected' : '' }}>Direction des Marchés Publics</option>
                <option value="Direction du Patrimoine" {{ old('service') === 'Direction du Patrimoine' ? 'selected' : '' }}>Direction du Patrimoine</option>
                <option value="Recette des Finances" {{ old('service') === 'Recette des Finances' ? 'selected' : '' }}>Recette des Finances</option>
                <option value="Paierie Générale" {{ old('service') === 'Paierie Générale' ? 'selected' : '' }}>Paierie Générale</option>
                <option value="Direction Générale du Trésor" {{ old('service') === 'Direction Générale du Trésor' ? 'selected' : '' }}>Direction Générale du Trésor</option>
                <option value="Direction des Ressources Humaines" {{ old('service') === 'Direction des Ressources Humaines' ? 'selected' : '' }}>Direction des Ressources Humaines</option>
                <option value="Direction des Systèmes d'Information" {{ old('service') === 'Direction des Systèmes d\'Information' ?  'selected' : '' }}>Direction des Systèmes d'Information</option>
                <option value="Autre" {{ old('service') === 'Autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>
 

            <div class="form-group">
                <label>Description (optionnel)</label>
                <textarea name="description" placeholder="Informations complémentaires sur le document...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Fichier (PDF ou image, 10 Mo max)</label>
                <input type="file" name="fichier" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <button type="submit" class="btn-primary">Déposer le document</button>
        </form>
    </div>

    <a href="{{ route('documents.index') }}" class="back-link">&larr; Retour à la liste</a>

</body>
</html>