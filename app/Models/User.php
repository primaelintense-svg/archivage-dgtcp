<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    // ---- Rôles (RG2 : un seul rôle par utilisateur) ----

    public function estAgentComptable(): bool
    {
        return $this->role === 'agent_comptable';
    }

    public function estArchiviste(): bool
    {
        return $this->role === 'archiviste';
    }

    public function estAdministrateur(): bool
    {
        return $this->role === 'administrateur';
    }

    public function estVisiteur(): bool
    {
        return $this->role === 'visiteur';
    }

    // ---- Relations ----

    public function documentsDeposes()
    {
        return $this->hasMany(Document::class, 'utilisateur_depot_id');
    }

    public function documentsTraites()
    {
        return $this->hasMany(Document::class, 'utilisateur_traitant_id');
    }

    public function notificationsDocuments()
    {
        return $this->hasMany(NotificationDocument::class, 'utilisateur_destinataire_id');
    }

    public function journauxActivite()
    {
        return $this->hasMany(JournalActivite::class, 'utilisateur_id');
    }

    // ---- Scopes ----

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}
