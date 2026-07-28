<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public const DUREE_CONSERVATION_ANNEES = 10;

    protected $fillable = [
        'titre',
        'reference',
        'statut',
        'motif_rejet',
        'type_document',
        'service',
        'exercice_comptable',
        'montant',
        'description',
        'date_depot',
        'date_traitement',
        'date_expiration',
        'utilisateur_depot_id',
        'utilisateur_traitant_id',
        'classification_id',
    ];

    protected function casts(): array
    {
        return [
            'date_depot' => 'datetime',
            'date_traitement' => 'datetime',
            'date_expiration' => 'date',
            'montant' => 'decimal:2',
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

    public function fichier()
    {
        return $this->hasOne(Fichier::class);
    }

    public function indexations()
    {
        return $this->hasMany(Indexation::class);
    }

    public function notifications()
    {
        return $this->hasMany(NotificationDocument::class);
    }

    // ---- Scopes ----

    public function scopeArchives($query)
    {
        return $query->where('statut', 'archive');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    // ---- Helpers de cycle de vie ----

    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function peutEtreModifie(): bool
    {
        return $this->estEnAttente();
    }

    public static function genererReference(): string
    {
        $annee = now()->year;

        do {
            $dernier = self::where('reference', 'like', "DGTCP-{$annee}-%")->count() + 1;
            $reference = sprintf('DGTCP-%d-%04d', $annee, $dernier);
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    public static function calculerDateExpiration(\DateTimeInterface $dateDepot): \Carbon\Carbon
    {
        return \Carbon\Carbon::instance($dateDepot)->addYears(self::DUREE_CONSERVATION_ANNEES);
    }

    public function expireBientot(int $seuilJours = 90): bool
    {
        if (! $this->date_expiration) {
            return false;
        }

        return now()->diffInDays($this->date_expiration, false) <= $seuilJours;
    }
}
