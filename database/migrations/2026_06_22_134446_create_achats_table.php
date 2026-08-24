<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // ACH-2026-08-05-001
            $table->foreignId('fournisseur_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('statut', [
                'brouillon',
                'confirme',
                'recu_partiel',
                'recu_total',
                'annule'
            ])->default('brouillon');
            $table->date('date_achat');
            $table->date('date_reception_prevue')->nullable();
            $table->date('date_reception')->nullable();
            $table->integer('mt_total')->default(0); // calculé depuis les lignes
            $table->integer('mt_paye')->default(0);
            $table->date('date_paiement')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};