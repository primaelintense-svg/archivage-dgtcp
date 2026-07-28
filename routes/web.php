<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchivisteController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChangerMotDePasseController;
use App\Http\Controllers\DemandeCompteController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ---- Authentification + demande d'accès (accessible sans être connecté) ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/demande-acces', [DemandeCompteController::class, 'create'])->name('demandeCompte.create');
    Route::post('/demande-acces', [DemandeCompteController::class, 'store'])->name('demandeCompte.store');

});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Page de changement de mot de passe forcé — accessible à tout utilisateur connecté
    Route::get('/changer-mot-de-passe', [ChangerMotDePasseController::class, 'show'])->name('changerMotDePasse.show');
    Route::post('/changer-mot-de-passe', [ChangerMotDePasseController::class, 'update'])->name('changerMotDePasse.update');
});

// ---- Agent comptable ----
Route::middleware(['auth', 'doit_changer_mdp', 'role:agent_comptable'])->group(function () {
    Route::get('/agent/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/agent/documents/creer', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/agent/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/agent/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
});

// ---- Archiviste ----
Route::middleware(['auth', 'doit_changer_mdp', 'role:archiviste'])->prefix('archiviste')->group(function () {
    Route::get('/documents', [ArchivisteController::class, 'index'])->name('archiviste.index');
    Route::get('/documents/{document}', [ArchivisteController::class, 'show'])->name('archiviste.show');
    Route::get('/documents/{document}/fichier', [ArchivisteController::class, 'voirFichier'])->name('documents.fichier');
    Route::post('/documents/{document}/valider', [ArchivisteController::class, 'valider'])->name('archiviste.valider');
    Route::post('/documents/{document}/rejeter', [ArchivisteController::class, 'rejeter'])->name('archiviste.rejeter');
    Route::post('/documents/{document}/classer', [ArchivisteController::class, 'classer'])->name('archiviste.classer');
});

// ---- Administration ----
Route::middleware(['auth', 'doit_changer_mdp', 'role:administrateur'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/rapports/etat-recapitulatif', [RapportController::class, 'etatRecapitulatif'])->name('rapports.etatRecapitulatif');
    Route::get('/admin/rapports/documents-expirant', [RapportController::class, 'documentsExpirant'])->name('rapports.documentsExpirant');

    Route::get('/admin/demandes-acces', [DemandeCompteController::class, 'index'])->name('demandeCompte.index');
    Route::post('/admin/demandes-acces/{demande}/approuver', [DemandeCompteController::class, 'approuver'])->name('demandeCompte.approuver');
    Route::post('/admin/demandes-acces/{demande}/rejeter', [DemandeCompteController::class, 'rejeter'])->name('demandeCompte.rejeter');

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-actif', [UserController::class, 'toggleActif'])->name('users.toggleActif');
     Route::delete('/users/{user}/supprimer', [UserController::class, 'destroy'])->name('users.destroy');
});

// ---- Visiteur + Archiviste : recherche multicritère ----
Route::middleware(['auth', 'doit_changer_mdp', 'role:visiteur,archiviste'])->prefix('recherche')->group(function () {
    Route::get('/', [RechercheController::class, 'index'])->name('recherche.index');
    Route::get('/{document}', [RechercheController::class, 'show'])->name('recherche.show');
    Route::get('/{document}/telecharger', [RechercheController::class, 'telecharger'])->name('recherche.telecharger');
});

Route::get('/', function () {
    return redirect('/login');
});
