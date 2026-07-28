<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    public function run(): void
    {
        // Classement par type de document comptable
        $classifications = [
            ['code' => 'FACT', 'libelle' => 'Factures'],
            ['code' => 'BORD-DEP', 'libelle' => 'Bordereaux de dépenses'],
            ['code' => 'BORD-EMI', 'libelle' => "Bordereaux d'émission"],
            ['code' => 'MAND', 'libelle' => 'Mandats de paiement'],
            ['code' => 'ORD-REC', 'libelle' => 'Ordres de recette'],
            ['code' => 'TIT-PER', 'libelle' => 'Titres de perception'],
            ['code' => 'RECU', 'libelle' => 'Reçus'],
            ['code' => 'QUIT', 'libelle' => 'Quittances'],
            ['code' => 'BON-CMD', 'libelle' => 'Bons de commande'],
            ['code' => 'DECOMPTE', 'libelle' => 'Décomptes'],
            ['code' => 'ASF', 'libelle' => 'Attestations de service fait'],
            ['code' => 'PJD', 'libelle' => 'Pièces justificatives de dépense'],
            ['code' => 'ERB', 'libelle' => 'États de rapprochement bancaire'],
            ['code' => 'AV', 'libelle' => 'Avis de virement'],
            ['code' => 'CHEQUE', 'libelle' => 'Chèques'],
            ['code' => 'CERT-ADM', 'libelle' => 'Certificats administratifs'],
            ['code' => 'AUTRE', 'libelle' => 'Autres documents'],
        ];

        foreach ($classifications as $c) {
            Classification::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}