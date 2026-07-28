<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Afficher le formulaire de création d'un utilisateur.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Enregistrer un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['agent_comptable', 'archiviste', 'administrateur', 'visiteur', 'consultant'])],
        ]);

        User::create([
            'nom' => $donnees['nom'],
            'prenom' => $donnees['prenom'],
            'email' => $donnees['email'],
            'password' => Hash::make($donnees['password']),
            'role' => $donnees['role'],
            'actif' => true,
            'doit_changer_mot_de_passe' => true,
        ]);

        return redirect()->route('admin.dashboard')->with('succes', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher le formulaire de modification d'un utilisateur.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur existant.
     */
    public function update(Request $request, User $user)
    {
        $donnees = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['agent_comptable', 'archiviste', 'administrateur', 'visiteur', 'consultant'])],
        ]);

        $user->update($donnees);

        return redirect()->route('admin.dashboard')->with('succes', 'Utilisateur modifié avec succès.');
    }

    /**
     * Activer / désactiver un utilisateur.
     */
    public function toggleActif(User $user)
    {
        $user->update(['actif' => ! $user->actif]);

        return redirect()->route('admin.dashboard')->with('succes', 'Statut de l\'utilisateur mis à jour.');
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.dashboard')->with('succes', 'Utilisateur supprimé avec succès.');
    }
}