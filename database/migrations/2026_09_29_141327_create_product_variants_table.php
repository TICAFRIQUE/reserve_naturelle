<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        // ============================================================
        // PHASE 1 : Création de la table product_variants
        // ============================================================
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('reference_prod')->unique();   // SKU par variante
            $table->string('conditionnement');
            $table->unsignedInteger('prix_vente');
            $table->integer('qte_dispo')->default(0);
            $table->integer('stock_minimum')->default(0);
            $table->integer('cmp')->default(0);
            $table->boolean('actif')->default(true);
            $table->unique(['product_id', 'conditionnement']);
            $table->timestamps();
        });

        // ============================================================
        // PHASE 2 : Migration des données existantes
        // 1 variante "Unité" par produit existant
        // ============================================================
        DB::statement("
            INSERT INTO product_variants
                (product_id, reference_prod, conditionnement, prix_vente,
                 qte_dispo, stock_minimum, cmp, actif, created_at, updated_at)
            SELECT
                id,
                reference_prod,
                'Unité',
                prix_vente,
                qte_dispo,
                stock_minimum,
                cmp,
                1,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            FROM products
        ");
        // ============================================================
        // PHASE 3 : Ajout de product_variant_id sur les tables liées
        // + remplissage automatique via sous-requête
        // ============================================================
        $tables = [
            'cart_items',
            'order_items',
            'achat_product',
            'stock_mouvements',
            'inventaire_produits',
            'retour_client_produits',
            'retour_fournisseur_produits',
        ];

        foreach ($tables as $table) {
            // On ajoute la colonne nullable d'abord
            Schema::table($table, function (Blueprint $b) {
                $b->foreignId('product_variant_id')->nullable()->after('product_id')
                    ->constrained('product_variants')->restrictOnDelete();
            });
            // On remplit la colonne : à ce stade, 1 seule variante par produit
            DB::statement("
                UPDATE {$table}
                SET product_variant_id = (
                    SELECT v.id
                    FROM product_variants v
                    WHERE v.product_id = {$table}.product_id
                    LIMIT 1
                )
                WHERE product_id IS NOT NULL
            ");
        }
        // ============================================================
        // PHASE 4 : Rendre product_variant_id NOT NULL (optionnel)
        // À activer une fois que tu es sûr que toutes les lignes sont liées.
        // ============================================================
        // foreach ($tables as $table) {
        //     Schema::table($table, function (Blueprint $b) {
        //         $b->foreignId('product_variant_id')->nullable(false)->change();
        //     });
        // }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void{
        // ============================================================
        // Suppression des clés étrangères avant de supprimer la table
        // ============================================================
        $tables = [
            'cart_items',
            'order_items',
            'achat_product',
            'stock_mouvements',
            'inventaire_produits',
            'retour_client_produits',
            'retour_fournisseur_produits',
        ];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $b) {
                $b->dropForeign(['product_variant_id']);
                $b->dropColumn('product_variant_id');
            });
        }
        Schema::dropIfExists('product_variants');
    }
};