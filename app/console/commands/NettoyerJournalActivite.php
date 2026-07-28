<?php

namespace App\Console\Commands;

use App\Models\JournalActivite;
use Illuminate\Console\Command;

class NettoyerJournalActivite extends Command
{
    protected $signature = 'journal:nettoyer';

    protected $description = "Supprime les entrées du journal d'activité vieilles de plus de 48 heures";

    public function handle(): int
    {
        $seuil = now()->subHours(48);

        $supprimees = JournalActivite::where('date_action', '<', $seuil)->delete();

        $this->info("{$supprimees} entrée(s) du journal supprimée(s) (plus de 48h).");

        return self::SUCCESS;
    }
}
