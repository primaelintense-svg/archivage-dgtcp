<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        $this->assurerPasTropDeTentatives($request);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($this->cleLimitation($request), 60); // verrouille 60s de plus à chaque échec

            return back()
                ->withErrors(['email' => 'Identifiants incorrects.'])
                ->onlyInput('email');
        }

        // Compte désactivé par un administrateur : on refuse même avec le bon mot de passe
        if (! Auth::user()->actif) {
            Auth::logout();

            return back()
                ->withErrors(['email' => 'Ce compte a été désactivé. Contactez un administrateur.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($this->cleLimitation($request));
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

    // ---- Protection contre le brute-force ----
    // 5 tentatives max, ensuite verrouillage progressif par email + IP

    private function cleLimitation(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    private function assurerPasTropDeTentatives(Request $request): void
    {
        $cle = $this->cleLimitation($request);

        if (! RateLimiter::tooManyAttempts($cle, 5)) {
            return;
        }

        $secondes = RateLimiter::availableIn($cle);

        JournalActivite::enregistrer(
            null,
            'tentative_connexion_bloquee',
            "Trop de tentatives pour {$request->input('email')} depuis " . $request->ip()
        );

        throw ValidationException::withMessages([
            'email' => "Trop de tentatives échouées. Réessayez dans {$secondes} secondes.",
        ]);
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
