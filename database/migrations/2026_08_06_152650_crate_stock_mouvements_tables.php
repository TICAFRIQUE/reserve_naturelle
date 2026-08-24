<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'entree_achat',       // réception fournisseur
                'sortie_commande',    // expédition client
                'retour_fournisseur', // retour vers fournisseur
                'retour_client',      // retour depuis client
                'ajustement',         // correction manuelle
                'inventaire',         // inventaire physique
                'perte_casse',        // perte/casse
            ]);
            $table->enum('sens', ['entree', 'sortie']); // + ou -
            $table->integer('quantite'); // toujours positif
            $table->integer('stock_avant');
            $table->integer('stock_apres');
            $table->integer('cmp_avant')->default(0);
            $table->integer('cmp_apres')->default(0);
            // Lien polymorphique vers la source
            $table->nullableMorphs('source'); // source_type + source_id
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mouvements');
    }
};