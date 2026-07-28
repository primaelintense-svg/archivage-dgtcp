<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\JournalActivite;
use App\Models\NotificationDocument;
use Illuminate\Console\Command;

class AlerterExpirationDocuments extends Command
{
    protected $signature = 'documents:alerter-expiration {--jours=90}';

    protected $description = "Notifie les agents concernés par des documents dont la conservation expire bientôt";

    public function handle(): int
    {
        $seuilJours = (int) $this->option('jours');

        $documents = Document::query()
            ->where('statut', 'archive')
            ->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '<=', now()->addDays($seuilJours))
            ->get();

        $compteur = 0;

        foreach ($documents as $document) {
            // Évite de renvoyer une notification si une alerte a déjà été envoyée pour ce document
            $dejaAlerte = NotificationDocument::where('document_id', $document->id)
                ->where('message', 'like', 'Alerte expiration%')
                ->exists();

            if ($dejaAlerte) {
                continue;
            }

            NotificationDocument::create([
                'message' => "Alerte expiration : le document {$document->reference} arrive à expiration le {$document->date_expiration->format('d/m/Y')}.",
                'date_envoi' => now(),
                'lu' => false,
                'document_id' => $document->id,
                'utilisateur_destinataire_id' => $document->utilisateur_depot_id,
            ]);

            JournalActivite::enregistrer(
                null,
                'alerte_expiration',
                "Alerte d'expiration envoyée pour le document {$document->reference}"
            );

            $compteur++;
        }

        $this->info("{$compteur} alerte(s) d'expiration envoyée(s) sur {$documents->count()} document(s) concerné(s).");

        return self::SUCCESS;
    }
}
