<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('type_document', 100)->nullable()->after('reference');
            $table->string('service', 100)->nullable()->after('type_document');
            $table->string('exercice_comptable', 10)->nullable()->after('service');
            $table->decimal('montant', 15, 2)->nullable()->after('exercice_comptable');
            $table->text('description')->nullable()->after('montant');
            $table->date('date_expiration')->nullable()->after('date_traitement');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'type_document',
                'service',
                'exercice_comptable',
                'montant',
                'description',
                'date_expiration',
            ]);
        });
    }
};
