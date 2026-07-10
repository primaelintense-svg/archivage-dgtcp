<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_documents', function (Blueprint $table) {
            $table->id();
            $table->string('message', 255);
            $table->dateTime('date_envoi');
            $table->boolean('lu')->default(false);

            $table->foreignId('document_id')
                  ->constrained('documents')
                  ->cascadeOnDelete();

            // RG15 : toujours destinée à l'agent comptable ayant déposé le document
            $table->foreignId('utilisateur_destinataire_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_documents');
    }
};
