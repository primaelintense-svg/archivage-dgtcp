<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeCompte extends Model
{
    use HasFactory;

    protected $table = 'demandes_compte';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'motif',
        'statut',
        'motif_rejet',
        'date_demande',
        'date_traitement',
        'utilisateur_traitant_id',
    ];

    protected function casts(): array
    {
        return [
            'date_demande' => 'datetime',
            'date_traitement' => 'datetime',
        ];
    }

    public function administrateurTraitant()
    {
        return $this->belongsTo(User::class, 'utilisateur_traitant_id');
    }

    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }
}
