<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Document;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query()->with('classification', 'agentDepot');

        // RG14/RG20 : un visiteur ne voit que les documents archivés ;
        // l'archiviste peut rechercher parmi tous les statuts.
        $visibleParVisiteur = ! Auth::user()->estArchiviste();
        if ($visibleParVisiteur) {
            $query->where('statut', 'archive');
        }

        if ($request->filled('type_document')) {
            $query->where('type_document', $request->input('type_document'));
        }

        if ($request->filled('service')) {
            $query->where('service', $request->input('service'));
        }

        if ($request->filled('exercice_comptable')) {
            $query->where('exercice_comptable', $request->input('exercice_comptable'));
        }

        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->input('reference') . '%');
        }

        if ($request->filled('mots_cles')) {
            $motsCles = $request->input('mots_cles');
            $query->whereHas('indexations', function ($q) use ($motsCles) {
                $q->where('mots_cles', 'like', '%' . $motsCles . '%');
            });
        }

        $documents = $query->latest('date_depot')->paginate(15)->withQueryString();

        // ---- Valeurs disponibles pour guider la recherche (listes déroulantes) ----
        $baseValeurs = Document::query();
        if ($visibleParVisiteur) {
            $baseValeurs->where('statut', 'archive');
        }

        $typesDisponibles = (clone $baseValeurs)
            ->whereNotNull('type_document')
            ->distinct()
            ->orderBy('type_document')
            ->pluck('type_document');

        $servicesDisponibles = (clone $baseValeurs)
            ->whereNotNull('service')
            ->distinct()
            ->orderBy('service')
            ->pluck('service');

        $exercicesDisponibles = (clone $baseValeurs)
            ->whereNotNull('exercice_comptable')
            ->distinct()
            ->orderByDesc('exercice_comptable')
            ->pluck('exercice_comptable');

        return view('recherche.index', compact(
            'documents',
            'typesDisponibles',
            'servicesDisponibles',
            'exercicesDisponibles'
        ));
    }

    public function show(Document $document)
    {
        if (! Auth::user()->estArchiviste() && $document->statut !== 'archive') {
            abort(403, 'Ce document n\'est pas accessible.');
        }

        $document->load('fichier', 'classification', 'agentDepot', 'indexations');

        JournalActivite::enregistrer(
            Auth::id(),
            'consultation_document',
            "Consultation du document {$document->reference}"
        );

        return view('recherche.show', compact('document'));
    }

    public function telecharger(Document $document)
    {
        if (! Auth::user()->estArchiviste() && $document->statut !== 'archive') {
            abort(403, 'Ce document n\'est pas accessible.');
        }

        if (! $document->fichier) {
            abort(404, 'Aucun fichier associé à ce document.');
        }

        JournalActivite::enregistrer(
            Auth::id(),
            'telechargement_document',
            "Téléchargement du document {$document->reference}"
        );

        return Storage::download(
            $document->fichier->chemin_fichier,
            $document->fichier->nom
        );
    }
}
