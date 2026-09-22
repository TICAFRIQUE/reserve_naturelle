<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_depense_id')->constrained('categorie_depenses');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('montant');
            $table->date('date_depense');
            $table->string('mode_paiement')->default('especes'); // especes|cheque|virement|mobile_money
            $table->string('reference')->nullable(); // n° chèque/transaction
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('date_depense');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
