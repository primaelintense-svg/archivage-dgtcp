<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationDocument extends Model
{
    use HasFactory;

    protected $table = 'notifications_documents';

    protected $fillable = [
        'message',
        'date_envoi',
        'lu',
        'document_id',
        'utilisateur_destinataire_id',
    ];

    protected function casts(): array
    {
        return [
            'date_envoi' => 'datetime',
            'lu' => 'boolean',
        ];
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'utilisateur_destinataire_id');
    }
}
