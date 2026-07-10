<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indexations', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 100)->nullable();
            $table->string('exercice_comptable', 10)->nullable();
            $table->string('type_document', 100)->nullable();
            $table->string('service', 100)->nullable();
            $table->decimal('montant', 15, 2)->nullable();
            $table->string('mots_cles', 255)->nullable();

            // Un document peut avoir plusieurs entrées d'index (0,n)
            $table->foreignId('document_id')
                  ->constrained('documents')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indexations');
    }
};
