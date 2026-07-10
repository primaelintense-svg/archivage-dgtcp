<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'reference',
        'statut',
        'date_depot',
        'date_traitement',
        'utilisateur_depot_id',
        'utilisateur_traitant_id',
        'classification_id',
    ];

    protected function casts(): array
    {
        return [
            'date_depot' => 'datetime',
            'date_traitement' => 'datetime',
        ];
    }

    // ---- Relations ----

    public function agentDepot()
    {
        return $this->belongsTo(User::class, 'utilisateur_depot_id');
    }

    public function archivisteTraitant()
    {
        return $this->belongsTo(User::class, 'utilisateur_traitant_id');
    }

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    // Un document possède exactement un fichier (1,1)
    public function fichier()
    {
        return $this->hasOne(Fichier::class);
    }

    // Un document peut avoir plusieurs entrées d'index (0,n)
    public function indexations()
    {
        return $this->hasMany(Indexation::class);
    }

    // Un document peut générer plusieurs notifications (0,n)
    public function notifications()
    {
        return $this->hasMany(NotificationDocument::class);
    }

    // ---- Scopes utiles (RG14 : visibilité selon statut) ----

    public function scopeArchives($query)
    {
        return $query->where('statut', 'archive');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    // ---- Helpers de cycle de vie (RG6, RG7) ----

    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function peutEtreModifie(): bool
    {
        // RG16 : modifiable par l'agent uniquement tant qu'en attente
        return $this->estEnAttente();
    }
}
