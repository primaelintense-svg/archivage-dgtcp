<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'taille',
        'type_mime',
        'chemin_fichier',
        'document_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
