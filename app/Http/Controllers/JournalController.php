<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    // Historique des activités, consultable et filtrable par l'administrateur (RG17)
    public function index(Request $request)
    {
        $query = JournalActivite::query()->with('utilisateur');

        if ($request->filled('utilisateur_id')) {
            $query->where('utilisateur_id', $request->input('utilisateur_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_action', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_action', '<=', $request->input('date_fin'));
        }

        $entrees = $query->latest('date_action')->paginate(20)->withQueryString();

        $utilisateurs = User::orderBy('nom')->get();

        $actionsDisponibles = JournalActivite::query()
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('journal.index', compact('entrees', 'utilisateurs', 'actionsDisponibles'));
    }
}
