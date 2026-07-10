<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->unsignedInteger('taille'); // en octets
            $table->string('type_mime', 100);
            $table->string('chemin_fichier', 255);

            // RG : un document possède exactement un fichier (1,1)
            $table->foreignId('document_id')
                  ->unique()
                  ->constrained('documents')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichiers');
    }
};
