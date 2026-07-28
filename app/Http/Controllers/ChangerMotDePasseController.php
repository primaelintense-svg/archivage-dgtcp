<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangerMotDePasseController extends Controller
{
    public function show()
    {
        return view('auth.changer_mot_de_passe');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'mot_de_passe' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'mot_de_passe' => 'nouveau mot de passe',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($data['mot_de_passe']);
        $user->doit_changer_mot_de_passe = false;
        $user->save();

        JournalActivite::enregistrer(
            $user->id,
            'changement_mot_de_passe',
            'Changement du mot de passe temporaire à la première connexion'
        );

        return redirect('/recherche')->with('succes', 'Mot de passe mis à jour avec succès.');
    }
}
