<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 200);
            $table->string('reference', 100)->unique();
            $table->enum('statut', ['en_attente', 'valide', 'rejete', 'archive'])
                  ->default('en_attente');
            $table->dateTime('date_depot');
            $table->dateTime('date_traitement')->nullable();

            // RG11 : déposé par un agent comptable (obligatoire)
            $table->foreignId('utilisateur_depot_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // RG12 : traité par un archiviste (nul tant qu'en attente)
            $table->foreignId('utilisateur_traitant_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // RG8 : classification attribuée seulement après validation
            $table->foreignId('classification_id')
                  ->nullable()
                  ->constrained('classifications')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
