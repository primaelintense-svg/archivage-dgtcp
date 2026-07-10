<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $classifications = [
            ['code' => 'FACT', 'libelle' => 'Factures'],
            ['code' => 'BORD', 'libelle' => 'Bordereaux'],
            ['code' => 'MAND', 'libelle' => 'Mandats de paiement'],
            ['code' => 'RECU', 'libelle' => 'Reçus et quittances'],
        ];

        foreach ($classifications as $c) {
            Classification::create($c);
        }
    }
}
