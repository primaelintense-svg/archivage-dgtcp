<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Liste de tous les comptes (RG16)
    public function index()
    {
        $users = User::orderBy('nom')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    // Ajout d'un compte utilisateur (RG16 : administrateur uniquement)
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['agent_comptable', 'archiviste', 'administrateur', 'visiteur'])],
        ]);

        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'actif' => true,
        ]);

        JournalActivite::enregistrer(
            Auth::id(),
            'creation_utilisateur',
            "Création du compte {$user->email} ({$user->role})"
        );

        return redirect()->route('users.index')->with('succes', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Modification d'un compte (nom, prénom, email, rôle — mot de passe optionnel)
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['agent_comptable', 'archiviste', 'administrateur', 'visiteur'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->nom = $data['nom'];
        $user->prenom = $data['prenom'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        JournalActivite::enregistrer(
            Auth::id(),
            'modification_utilisateur',
            "Modification du compte {$user->email}"
        );

        return redirect()->route('users.index')->with('succes', 'Utilisateur modifié avec succès.');
    }

    // Active ou désactive un compte (RG16)
    public function toggleActif(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['erreur' => 'Vous ne pouvez pas désactiver votre propre compte.']);
        }

        $user->actif = ! $user->actif;
        $user->save();

        JournalActivite::enregistrer(
            Auth::id(),
            $user->actif ? 'activation_utilisateur' : 'desactivation_utilisateur',
            "Compte {$user->email} " . ($user->actif ? 'activé' : 'désactivé')
        );

        return back()->with('succes', 'Statut du compte mis à jour.');
    }
}
