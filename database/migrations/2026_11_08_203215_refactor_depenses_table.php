<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        // Aucune donnée à conserver
        DB::table('depenses')->delete();

        Schema::table('depenses', function (Blueprint $table) {
            $table->dropForeign(['categorie_depense_id']);
            $table->dropColumn(['categorie_depense_id', 'libelle', 'description', 'mode_paiement', 'reference']);

            $table->foreignId('libelle_depense_id')->after('id')
                ->constrained('libelle_depenses')->restrictOnDelete();
        });
    }

    public function down(): void{
        DB::table('depenses')->delete();

        Schema::table('depenses', function (Blueprint $table) {
            $table->dropForeign(['libelle_depense_id']);
            $table->dropColumn('libelle_depense_id');

            $table->foreignId('categorie_depense_id')->after('id')->constrained('categorie_depenses');
            $table->string('libelle')->after('categorie_depense_id');
            $table->text('description')->nullable();
            $table->string('mode_paiement')->default('especes');
            $table->string('reference')->nullable();
        });
    }
};