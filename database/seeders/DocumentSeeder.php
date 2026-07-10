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
        $classificationFacture = Classification::where('code', 'FACT')->first();

        // 1. Document en attente : déposé, pas encore traité
        $doc1 = Document::create([
            'titre' => 'Facture fournisseur SONEB janvier 2026',
            'reference' => 'DGTCP-2026-0001',
            'statut' => 'en_attente',
            'date_depot' => now()->subDays(2),
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
            'date_depot' => now()->subDays(5),
            'date_traitement' => now()->subDays(4),
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
            'date_depot' => now()->subDays(20),
            'date_traitement' => now()->subDays(18),
            'utilisateur_depot_id' => $agent->id,
            'utilisateur_traitant_id' => $archiviste->id,
            'classification_id' => $classificationFacture->id,
        ]);

        Fichier::create([
            'nom' => 'mandat_112_2025.pdf',
            'taille' => 312456,
            'type_mime' => 'application/pdf',
            'chemin_fichier' => 'documents/2025/12/mandat_112_2025.pdf',
            'document_id' => $doc3->id,
        ]);

        Indexation::create([
            'reference' => 'MAND-112-2025',
            'exercice_comptable' => '2025',
            'type_document' => 'Mandat de paiement',
            'service' => 'Direction du Budget',
            'montant' => 1250000.00,
            'mots_cles' => 'mandat, paiement, exercice 2025',
            'document_id' => $doc3->id,
        ]);

        NotificationDocument::create([
            'message' => 'Votre document DGTCP-2025-0347 a été validé et archivé.',
            'date_envoi' => now()->subDays(18),
            'lu' => true,
            'document_id' => $doc3->id,
            'utilisateur_destinataire_id' => $agent->id,
        ]);
    }
}
