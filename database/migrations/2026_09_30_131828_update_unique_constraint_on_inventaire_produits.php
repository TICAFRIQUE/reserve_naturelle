<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 1. Supprimer toutes les FK
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'inventaire_produits'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE inventaire_produits DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 2. Supprimer l'index unique problématique
        $indexes = DB::select("SHOW INDEX FROM inventaire_produits WHERE Key_name = 'inventaire_produits_inventaire_id_product_id_unique'");
        if (!empty($indexes)) {
            DB::statement("ALTER TABLE inventaire_produits DROP INDEX `inventaire_produits_inventaire_id_product_id_unique`");
        }

        // 3. Recréer les FK
        DB::statement("
            ALTER TABLE inventaire_produits
            ADD CONSTRAINT inventaire_produits_inventaire_id_foreign
            FOREIGN KEY (inventaire_id) REFERENCES inventaires(id) ON DELETE CASCADE
        ");
        DB::statement("
            ALTER TABLE inventaire_produits
            ADD CONSTRAINT inventaire_produits_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id)
        ");
        DB::statement("
            ALTER TABLE inventaire_produits
            ADD CONSTRAINT inventaire_produits_product_variant_id_foreign
            FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
        ");

        // 4. Nouvelle contrainte unique (variante)
        DB::statement("
            ALTER TABLE inventaire_produits
            ADD UNIQUE KEY inventaire_produits_inventaire_variant_unique (inventaire_id, product_variant_id)
        ");

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement("ALTER TABLE inventaire_produits DROP INDEX `inventaire_produits_inventaire_variant_unique`");

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'inventaire_produits'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE inventaire_produits DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        DB::statement("
            ALTER TABLE inventaire_produits
            ADD UNIQUE KEY inventaire_produits_inventaire_id_product_id_unique (inventaire_id, product_id)
        ");
        DB::statement("
            ALTER TABLE inventaire_produits
            ADD CONSTRAINT inventaire_produits_inventaire_id_foreign
            FOREIGN KEY (inventaire_id) REFERENCES inventaires(id) ON DELETE CASCADE
        ");
        DB::statement("
            ALTER TABLE inventaire_produits
            ADD CONSTRAINT inventaire_produits_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id)
        ");
        DB::statement("
            ALTER TABLE inventaire_produits
            ADD CONSTRAINT inventaire_produits_product_variant_id_foreign
            FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
        ");

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};