<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    use HasFactory;

    protected $table = 'journal_activites';

    protected $fillable = [
        'action',
        'date_action',
        'details',
        'utilisateur_id',
    ];

    protected function casts(): array
    {
        return [
            'date_action' => 'datetime',
        ];
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class);
    }

    // $utilisateurId peut être null (ex. tentative de connexion échouée, avant authentification)
    public static function enregistrer(?int $utilisateurId, string $action, ?string $details = null): self
    {
        return self::create([
            'utilisateur_id' => $utilisateurId,
            'action' => $action,
            'date_action' => now(),
            'details' => $details,
        ]);
    }
}
