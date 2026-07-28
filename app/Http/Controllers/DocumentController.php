<?php
namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\Fichier;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
class DocumentController extends Controller
{
    // Liste des documents déposés par l'agent connecté (RG : un agent voit ses propres dépôts)
    public function index()
    {
        $documents = Document::where('utilisateur_depot_id', Auth::id())
            ->with('classification')
            ->latest('date_depot')
            ->paginate(15);
        return view('documents.index', compact('documents'));
    }
    public function create()
    {
        return view('documents.create');
    }
    // Sert le fichier physique d'un document (contrôle d'accès identique à show())
    public function fichier(Document $document): StreamedResponse
    {
        if ($document->utilisateur_depot_id !== Auth::id() && ! Auth::user()->estArchiviste() && ! Auth::user()->estAdministrateur()) {
            abort(403);
        }
        $fichier = $document->fichier;
        if (! $fichier || ! Storage::disk('local')->exists($fichier->chemin_fichier)) {
            abort(404);
        }
        return Storage::disk('local')->response(
            $fichier->chemin_fichier,
            $fichier->nom
        );
    }
    // Dépôt d'un document comptable (RG11 : réservé à l'Agent comptable)
    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => ['required', 'string', 'max:200'],
            'type_document' => ['required', 'string', 'max:100'],
            'service' => ['required', 'string', 'max:100'],
            'exercice_comptable' => ['required', 'integer', 'digits:4'],
            'description' => ['nullable', 'string'],
            // Formats autorisés : PDF et images (scan de document). Taille max 10 Mo.
            'fichier' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);
        $document = Document::create([
            'titre' => $data['titre'],
            'reference' => Document::genererReference(),
            'statut' => 'en_attente',
            'type_document' => $data['type_document'],
            'service' => $data['service'],
            'exercice_comptable' => $data['exercice_comptable'],
            'description' => $data['description'] ?? null,
            'date_depot' => now(),
            'date_expiration' => Document::calculerDateExpiration(now()),
            'utilisateur_depot_id' => Auth::id(),
        ]);
        $fichierUpload = $request->file('fichier');
        $chemin = $fichierUpload->store('documents/' . now()->format('Y/m'), 'local');
        Fichier::create([
            'nom' => $fichierUpload->getClientOriginalName(),
            'taille' => $fichierUpload->getSize(),
            'type_mime' => $fichierUpload->getClientMimeType(),
            'chemin_fichier' => $chemin,
            'document_id' => $document->id,
        ]);
        JournalActivite::enregistrer(
            Auth::id(),
            'depot_document',
            "Dépôt du document {$document->reference} ({$document->titre})"
        );
        return redirect()->route('documents.index')
            ->with('succes', "Document déposé avec succès. Référence : {$document->reference}");
    }
    // Détail d'un document (contrôle : l'agent ne voit que ses propres documents)
    public function show(Document $document)
    {
        if ($document->utilisateur_depot_id !== Auth::id() && ! Auth::user()->estArchiviste() && ! Auth::user()->estAdministrateur()) {
            abort(403);
        }
        $document->load('fichier', 'classification', 'agentDepot', 'archivisteTraitant', 'indexations');
        return view('documents.show', compact('document'));
    }
}