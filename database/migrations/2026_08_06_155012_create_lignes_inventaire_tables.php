<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaire_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('qte_theorique'); // stock système
            $table->integer('qte_reelle');    // stock compté physiquement
            $table->integer('ecart');         // qte_reelle - qte_theorique
            $table->unique(['inventaire_id', 'product_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaire_produits');
    }
};