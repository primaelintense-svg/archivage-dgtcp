<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indexation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'exercice_comptable',
        'type_document',
        'service',
        'montant',
        'mots_cles',
        'document_id',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
        ];
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
