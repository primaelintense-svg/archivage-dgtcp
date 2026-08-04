<?php

namespace Database\Seeders;

use App\Models\Classification;
use App\Models\Document;
use App\Models\Fichier;
use App\Models\Indexation;
use App\Models\NotificationDocument;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $agent = User::where('role', 'agent_comptable')->first();
        $archiviste = User::where('role', 'archiviste')->first();
        $classificationFacture = Classification::where('code', 'ARC-2026-A')->first();
        $classificationMandat = Classification::where('code', 'MAND')->first();

        // 1. Document en attente : déposé, pas encore traité
        $doc1 = Document::create([
            'titre' => 'Facture fournisseur SONEB janvier 2026',
            'reference' => 'DGTCP-2026-0001',
            'statut' => 'en_attente',
            'type_document' => 'Facture',
            'service' => 'Direction du Budget',
            'exercice_comptable' => '2026',
            'montant' => 125000.00,
            'description' => 'Facture d\'eau et assainissement, janvier 2026.',
            'date_depot' => now()->subDays(2),
            'date_expiration' => Document::calculerDateExpiration(now()->subDays(2)),
            'utilisateur_depot_id' => $agent->id,
        ]);

        Fichier::create([
            'nom' => 'facture_soneb_janv2026.pdf',
            'taille' => 245678,
            'type_mime' => 'application/pdf',
            'chemin_fichier' => 'documents/2026/01/facture_soneb_janv2026.pdf',
            'document_id' => $doc1->id,
        ]);

        // 2. Document rejeté : traité par l'archiviste, notification envoyée
        $doc2 = Document::create([
            'titre' => 'Bordereau de dépenses février 2026',
            'reference' => 'DGTCP-2026-0002',
            'statut' => 'rejete',
            'motif_rejet' => 'Pièce jointe illisible, merci de redéposer un scan de meilleure qualité.',
            'type_document' => 'Bordereau',
            'service' => 'Direction de la Comptabilité Publique',
            'exercice_comptable' => '2026',
            'montant' => 89000.00,
            'description' => 'Bordereau des dépenses courantes de février.',
            'date_depot' => now()->subDays(5),
            'date_traitement' => now()->subDays(4),
            'date_expiration' => Document::calculerDateExpiration(now()->subDays(5)),
            'utilisateur_depot_id' => $agent->id,
            'utilisateur_traitant_id' => $archiviste->id,
        ]);

        Fichier::create([
            'nom' => 'bordereau_fev2026.pdf',
            'taille' => 189234,
            'type_mime' => 'application/pdf',
            'chemin_fichier' => 'documents/2026/02/bordereau_fev2026.pdf',
            'document_id' => $doc2->id,
        ]);

        NotificationDocument::create([
            'message' => 'Votre document DGTCP-2026-0002 a été rejeté : pièce jointe illisible.',
            'date_envoi' => now()->subDays(4),
            'lu' => false,
            'document_id' => $doc2->id,
            'utilisateur_destinataire_id' => $agent->id,
        ]);

        // 3. Document archivé : cycle complet (validé, classé, indexé)
        $doc3 = Document::create([
            'titre' => 'Mandat de paiement N°112 - Exercice 2025',
            'reference' => 'DGTCP-2025-0347',
            'statut' => 'archive',
            'type_document' => 'Mandat de paiement',
            'service' => 'Direction du Budget',
            'exercice_comptable' => '2025',
            'montant' => 1250000.00,
            'description' => 'Mandat de paiement des fournisseurs, exercice 2025.',
            'date_depot' => now()->subDays(20),
            'date_traitement' => now()->subDays(18),
            'date_expiration' => Document::calculerDateExpiration(now()->subDays(20)),
            'utilisateur_depot_id' => $agent->id,
            'utilisateur_traitant_id' => $archiviste->id,
            'classification_id' => $classificationMandat->id,
        ]);

        Fichier::create([
            'nom' => 'mandat_112_2025.pdf',
            'taille' => 312456,
            'type_mime' => 'application/pdf',
            'chemin_fichier' => 'documents/2025/12/mandat_112_2025.pdf',
            'document_id' => $doc3->id,
        ]);

        Indexation::create([
            'mots_cles' => 'mandat, paiement, exercice 2025',
            'reference' => $doc3->reference,
            'exercice_comptable' => $doc3->exercice_comptable,
            'type_document' => $doc3->type_document,
            'service' => $doc3->service,
            'montant' => $doc3->montant,
            'document_id' => $doc3->id,
        ]);

        NotificationDocument::create([
            'message' => 'Votre document DGTCP-2025-0347 a été validé et archivé.',
            'date_envoi' => now()->subDays(18),
            'lu' => true,
            'document_id' => $doc3->id,
            'utilisateur_destinataire_id' => $agent->id,
        ]);

        // 4. Deuxième document archivé, pour que les listes déroulantes de recherche
        //    proposent plusieurs valeurs différentes
        $doc4 = Document::create([
            'titre' => 'Reçu de quittance loyer bâtiment annexe',
            'reference' => 'DGTCP-2025-0348',
            'statut' => 'archive',
            'type_document' => 'Reçu',
            'service' => 'Direction du Patrimoine',
            'exercice_comptable' => '2025',
            'montant' => 45000.00,
            'description' => 'Quittance de loyer du bâtiment annexe, décembre 2025.',
            'date_depot' => now()->subDays(25),
            'date_traitement' => now()->subDays(22),
            'date_expiration' => Document::calculerDateExpiration(now()->subDays(25)),
            'utilisateur_depot_id' => $agent->id,
            'utilisateur_traitant_id' => $archiviste->id,
            'classification_id' => $archiviste->id,
        ]);

        Fichier::create([
            'nom' => 'quittance_loyer_dec2025.pdf',
            'taille' => 98234,
            'type_mime' => 'application/pdf',
            'chemin_fichier' => 'documents/2025/12/quittance_loyer_dec2025.pdf',
            'document_id' => $doc4->id,
        ]);

        Indexation::create([
            'mots_cles' => 'loyer, quittance, patrimoine',
            'reference' => $doc4->reference,
            'exercice_comptable' => $doc4->exercice_comptable,
            'type_document' => $doc4->type_document,
            'service' => $doc4->service,
            'montant' => $doc4->montant,
            'document_id' => $doc4->id,
        ]);
    }
}
