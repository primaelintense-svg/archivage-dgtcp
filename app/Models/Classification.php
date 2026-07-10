<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'libelle',
    ];

    // Une classification regroupe plusieurs documents (0,n)
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
