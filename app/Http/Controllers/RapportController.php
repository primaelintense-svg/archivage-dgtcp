<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    // État récapitulatif des documents archivés, groupés par service puis par exercice
    public function etatRecapitulatif()
    {
        $documents = Document::where('statut', 'archive')
            ->with('classification')
            ->orderBy('service')
            ->orderBy('exercice_comptable')
            ->get()
            ->groupBy('service');

        $totalGeneral = Document::where('statut', 'archive')->count();
        $montantGeneral = Document::where('statut', 'archive')->sum('montant');

        $pdf = Pdf::loadView('rapports.etat_recapitulatif', compact('documents', 'totalGeneral', 'montantGeneral'));

        return $pdf->download('etat-recapitulatif-' . now()->format('Y-m-d') . '.pdf');
    }

    // Liste des documents arrivant à expiration
    public function documentsExpirant()
    {
        $documents = Document::where('statut', 'archive')
            ->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '<=', now()->addDays(90))
            ->orderBy('date_expiration')
            ->with('agentDepot')
            ->get();

        $pdf = Pdf::loadView('rapports.documents_expirant', compact('documents'));

        return $pdf->download('documents-expirant-' . now()->format('Y-m-d') . '.pdf');
    }
}
