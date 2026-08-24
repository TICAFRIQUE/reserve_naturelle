<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retour_fournisseur_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retour_fournisseur_id')->constrained('retours_fournisseur')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('qte_retournee');
            $table->decimal('prix_unitaire', 10, 2);
            $table->text('motif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retour_fournisseur_produits');
    }
};