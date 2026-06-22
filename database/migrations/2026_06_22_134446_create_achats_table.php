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
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->integer('qte_achetee');
            $table->integer('PU_achat');
            $table->integer('mt_total');
            $table->date('date_achat');
            $table->time('h_achat');
            $table->string('num_facture')->unique();
            $table->enum('statut', ['en_attente', 'recu', 'annule'])->default('en_attente');
            $table->integer('mt_paye')->default(0);
            $table->date('date_paiement')->nullable();
            $table->date('date_reception')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};