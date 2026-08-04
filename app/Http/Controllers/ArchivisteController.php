<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Document;
use App\Models\Indexation;
use App\Models\JournalActivite;
use App\Models\NotificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArchivisteController extends Controller
{
    // Page unique : TOUS les documents, quel que soit leur statut
    public function index(Request $request)
    {
        $query = Document::query()->with('agentDepot', 'classification');

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        $documents = $query->latest('date_depot')->paginate(20)->withQueryString();

        return view('archiviste.index', compact('documents'));
    }

    // Consultation d'un document, quel que soit son statut — les actions possibles
    // (valider/rejeter/classer) sont déterminées dans la vue selon le statut actuel
    public function show(Document $document)
    {
        $document->load('fichier', 'agentDepot', 'archivisteTraitant', 'classification', 'indexations');

        $classifications = Classification::orderBy('libelle')->get();

        return view('archiviste.show', compact('document', 'classifications'));
    }
    public function voirFichier(Document $document)
{
    if (! $document->fichier) {
        abort(404, 'Aucun fichier associé à ce document.');
    }

    JournalActivite::enregistrer(
        Auth::id(),
        'consultation_fichier',
        "Consultation du fichier du document {$document->reference}"
    );

    return Storage::response(
        $document->fichier->chemin_fichier,
        $document->fichier->nom
    );
}

    public function valider(Request $request, Document $document)
    {
        if (! $document->estEnAttente()) {
            return back()->withErrors(['erreur' => 'Ce document a déjà été traité.']);
        }

        $data = $request->validate([
            'mots_cles' => ['nullable', 'string', 'max:255'],
        ]);

        $document->statut = 'valide';
        $document->utilisateur_traitant_id = Auth::id();
        $document->date_traitement = now();
        $document->motif_rejet = null;
        $document->save();

        if (! empty($data['mots_cles'])) {
            Indexation::create([
                'mots_cles' => $data['mots_cles'],
                'reference' => $document->reference,
                'exercice_comptable' => $document->exercice_comptable,
                'type_document' => $document->type_document,
                'service' => $document->service,
                'montant' => $document->montant,
                'document_id' => $document->id,
            ]);
        }

        NotificationDocument::create([
            'message' => "Votre document {$document->reference} a été validé.",
            'date_envoi' => now(),
            'lu' => false,
            'document_id' => $document->id,
            'utilisateur_destinataire_id' => $document->utilisateur_depot_id,
        ]);

        JournalActivite::enregistrer(
            Auth::id(),
            'validation_document',
            "Validation du document {$document->reference}"
        );

        return redirect()->route('archiviste.show', $document)
            ->with('succes', "Document {$document->reference} validé et indexé.");
    }

     
    public function rejeter(Request $request, Document $document)
    {
        if (! $document->estEnAttente()) {
            return back()->withErrors(['erreur' => 'Ce document a déjà été traité.']);
        }

        $data = $request->validate([
            'motif_rejet' => ['required', 'string', 'max:1000'],
        ]);

        $document->statut = 'rejete';
        $document->motif_rejet = $data['motif_rejet'];
        $document->utilisateur_traitant_id = Auth::id();
        $document->date_traitement = now();
        $document->save();

        NotificationDocument::create([
            'message' => "Votre document {$document->reference} a été rejeté : {$data['motif_rejet']}",
            'date_envoi' => now(),
            'lu' => false,
            'document_id' => $document->id,
            'utilisateur_destinataire_id' => $document->utilisateur_depot_id,
        ]);

        JournalActivite::enregistrer(
            Auth::id(),
            'rejet_document',
            "Rejet du document {$document->reference} — motif : {$data['motif_rejet']}"
        );

        return redirect()->route('archiviste.show', $document)
            ->with('succes', "Document {$document->reference} rejeté.");
    }

    // Classement d'un document validé = attribution d'une classification + passage à archive
    public function classer(Request $request, Document $document)
    {
        if ($document->statut !== 'valide') {
            return back()->withErrors(['erreur' => 'Seul un document validé peut être classé.']);
        }

        $data = $request->validate([
            'classification_id' => ['required', 'exists:classifications,id'],
        ]);

        $document->classification_id = $data['classification_id'];
        $document->statut = 'archive';
        $document->save();

        JournalActivite::enregistrer(
            Auth::id(),
            'classement_document',
            "Classement et archivage du document {$document->reference}"
        );

        return redirect()->route('archiviste.show', $document)
            ->with('succes', "Document {$document->reference} classé et archivé.");
    }
}