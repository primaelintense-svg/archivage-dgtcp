<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_activites', function (Blueprint $table) {
            $table->id();
            $table->string('action', 100);
            $table->dateTime('date_action');
            $table->text('details')->nullable();

            // RG18 : chaque action est rattachée à l'utilisateur qui l'a effectuée
            $table->foreignId('utilisateur_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_activites');
    }
};
