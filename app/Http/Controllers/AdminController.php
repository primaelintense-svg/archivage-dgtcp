<?php

namespace App\Http\Controllers;

use App\Models\DemandeCompte;
use App\Models\Document;
use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'total_documents' => Document::count(),
            'en_attente' => Document::where('statut', 'en_attente')->count(),
            'valides' => Document::where('statut', 'valide')->count(),
            'rejetes' => Document::where('statut', 'rejete')->count(),
            'archives' => Document::where('statut', 'archive')->count(),
            'utilisateurs_actifs' => User::where('actif', true)->count(),
        ];

        $parType = Document::query()
            ->whereNotNull('type_document')
            ->selectRaw('type_document, count(*) as total')
            ->groupBy('type_document')
            ->orderByDesc('total')
            ->pluck('total', 'type_document');

        $parService = Document::query()
            ->whereNotNull('service')
            ->selectRaw('service, count(*) as total')
            ->groupBy('service')
            ->orderByDesc('total')
            ->pluck('total', 'service');

        $parEtat = [
            'En attente' => $stats['en_attente'],
            'Validé' => $stats['valides'],
            'Rejeté' => $stats['rejetes'],
            'Archivé' => $stats['archives'],
        ];

        $documentsExpirantBientot = Document::query()
            ->where('statut', 'archive')
            ->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '<=', now()->addDays(90))
            ->orderBy('date_expiration')
            ->with('agentDepot')
            ->get();

        $demandesCompteEnAttente = DemandeCompte::where('statut', 'en_attente')->count();

        // ---- Journal des activités : liste continue, sans pagination ----
        // (cohérent avec le nettoyage automatique toutes les 48h : le volume reste limité)
        $journalQuery = JournalActivite::query()->with('utilisateur');

        if ($request->filled('utilisateur_id')) {
            $journalQuery->where('utilisateur_id', $request->input('utilisateur_id'));
        }
        if ($request->filled('action')) {
            $journalQuery->where('action', $request->input('action'));
        }
        if ($request->filled('date_debut')) {
            $journalQuery->whereDate('date_action', '>=', $request->input('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $journalQuery->whereDate('date_action', '<=', $request->input('date_fin'));
        }

        $entrees = $journalQuery->latest('date_action')->get();

        $actionsDisponibles = JournalActivite::query()->distinct()->orderBy('action')->pluck('action');

        $users = User::orderBy('nom')->paginate(10, ['*'], 'page_users')->withQueryString();

        return view('admin.dashboard', compact(
            'stats',
            'parType',
            'parService',
            'parEtat',
            'documentsExpirantBientot',
            'demandesCompteEnAttente',
            'entrees',
            'actionsDisponibles',
            'users'
        ));
    }
}
