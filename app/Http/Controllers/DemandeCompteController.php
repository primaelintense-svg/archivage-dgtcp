<?php

namespace App\Http\Controllers;

use App\Mail\CompteCreeMail;
use App\Models\DemandeCompte;
use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DemandeCompteController extends Controller
{
    // ---- Partie publique (visible sans compte) ----

    public function create()
    {
        return view('demande_compte.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'motif' => ['nullable', 'string', 'max:500'],
        ]);

        DemandeCompte::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'motif' => $data['motif'] ?? null,
            'statut' => 'en_attente',
            'date_demande' => now(),
        ]);

        return redirect()->route('login')
            ->with('succes', 'Votre demande a été envoyée. Un administrateur va l\'examiner et vous contactera par email.');
    }

    // ---- Partie administrateur ----

    public function index()
    {
        $demandes = DemandeCompte::where('statut', 'en_attente')
            ->latest('date_demande')
            ->paginate(15);

        return view('demande_compte.index', compact('demandes'));
    }

    // Approuve la demande : crée le compte Visiteur et envoie le mot de passe par email
    public function approuver(DemandeCompte $demande)
    {
        if (! $demande->estEnAttente()) {
            return back()->withErrors(['erreur' => 'Cette demande a déjà été traitée.']);
        }

        if (User::where('email', $demande->email)->exists()) {
            return back()->withErrors(['erreur' => 'Un compte existe déjà avec cet email.']);
        }

        $motDePasseTemporaire = Str::password(10, symbols: false);

        $user = User::create([
            'nom' => $demande->nom,
            'prenom' => $demande->prenom,
            'email' => $demande->email,
            'password' => Hash::make($motDePasseTemporaire),
            'role' => 'visiteur',
            'actif' => true,
            'doit_changer_mot_de_passe' => true,
        ]);

        Mail::to($user->email)->send(new CompteCreeMail($user, $motDePasseTemporaire));

        $demande->statut = 'approuvee';
        $demande->date_traitement = now();
        $demande->utilisateur_traitant_id = Auth::id();
        $demande->save();

        JournalActivite::enregistrer(
            Auth::id(),
            'approbation_demande_compte',
            "Compte visiteur créé pour {$demande->email}, identifiants envoyés par email"
        );

        return redirect()->route('demandeCompte.index')
            ->with('succes', "Compte créé pour {$demande->email}. Les identifiants ont été envoyés par email.");
    }

    public function rejeter(Request $request, DemandeCompte $demande)
    {
        if (! $demande->estEnAttente()) {
            return back()->withErrors(['erreur' => 'Cette demande a déjà été traitée.']);
        }

        $data = $request->validate([
            'motif_rejet' => ['required', 'string', 'max:500'],
        ]);

        $demande->statut = 'rejetee';
        $demande->motif_rejet = $data['motif_rejet'];
        $demande->date_traitement = now();
        $demande->utilisateur_traitant_id = Auth::id();
        $demande->save();

        JournalActivite::enregistrer(
            Auth::id(),
            'rejet_demande_compte',
            "Demande de compte rejetée pour {$demande->email} — motif : {$data['motif_rejet']}"
        );

        return redirect()->route('demandeCompte.index')->with('succes', 'Demande rejetée.');
    }
}
