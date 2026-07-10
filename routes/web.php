<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ---- Authentification (accessible sans être connecté) ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // throttle:10,1 = 10 requêtes/minute par IP en filet de sécurité supplémentaire,
    // en plus du verrouillage par email géré dans le contrôleur (5 tentatives)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ---- Espaces par rôle (RG3 : accès selon le rôle) ----

Route::middleware(['auth', 'role:agent_comptable'])->prefix('agent')->group(function () {
    Route::get('/documents', function () {
        return 'Espace Agent comptable — liste des documents déposés (à brancher sur DocumentController)';
    });
});

Route::middleware(['auth', 'role:archiviste'])->prefix('archiviste')->group(function () {
    Route::get('/documents', function () {
        return 'Espace Archiviste — documents à traiter (à brancher sur DocumentController)';
    });
});

// ---- Administration (RG16, RG17) ----
Route::middleware(['auth', 'role:administrateur'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return 'Espace Administrateur — gestion des utilisateurs et journaux';
    });
});

Route::middleware(['auth', 'role:administrateur'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-actif', [UserController::class, 'toggleActif'])->name('users.toggleActif');
});

Route::middleware(['auth', 'role:visiteur,archiviste'])->group(function () {
    Route::get('/recherche', function () {
        return 'Recherche de documents archivés (à brancher sur DocumentController)';
    });
});

Route::get('/', function () {
    return redirect('/login');
});
