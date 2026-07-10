<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Affiche le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Traite la tentative de connexion (RG1 : authentification obligatoire)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Identifiants incorrects.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        JournalActivite::enregistrer(
            Auth::id(),
            'connexion',
            'Connexion réussie depuis ' . $request->ip()
        );

        return redirect()->intended($this->redirectPathSelonRole());
    }

    // Déconnexion
    public function logout(Request $request)
    {
        JournalActivite::enregistrer(
            Auth::id(),
            'deconnexion',
            'Déconnexion depuis ' . $request->ip()
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // RG3 : redirige vers l'espace correspondant au rôle
    private function redirectPathSelonRole(): string
    {
        return match (Auth::user()->role) {
            'agent_comptable' => '/agent/documents',
            'archiviste' => '/archiviste/documents',
            'administrateur' => '/admin/dashboard',
            'visiteur' => '/recherche',
            default => '/',
        };
    }
}
