<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Désactiver temporairement les vérifications FK
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 2. Récupérer toutes les FK de la table
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'achat_product'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // 3. Supprimer toutes les FK
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE achat_product DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 4. Supprimer l'index unique problématique
        $indexes = DB::select("SHOW INDEX FROM achat_product WHERE Key_name = 'achat_product_achat_id_product_id_unique'");
        if (!empty($indexes)) {
            DB::statement("ALTER TABLE achat_product DROP INDEX `achat_product_achat_id_product_id_unique`");
        }

        // 5. Recréer les FK
        DB::statement("
            ALTER TABLE achat_product
            ADD CONSTRAINT achat_product_achat_id_foreign
            FOREIGN KEY (achat_id) REFERENCES achats(id) ON DELETE CASCADE
        ");
        DB::statement("
            ALTER TABLE achat_product
            ADD CONSTRAINT achat_product_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id)
        ");
        DB::statement("
            ALTER TABLE achat_product
            ADD CONSTRAINT achat_product_product_variant_id_foreign
            FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
        ");

        // 6. Ajouter la nouvelle contrainte unique (variante)
        DB::statement("
            ALTER TABLE achat_product
            ADD UNIQUE KEY achat_product_achat_variant_unique (achat_id, product_variant_id)
        ");

        // 7. Réactiver les vérifications FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement("ALTER TABLE achat_product DROP INDEX `achat_product_achat_variant_unique`");

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'achat_product'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE achat_product DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        DB::statement("
            ALTER TABLE achat_product
            ADD UNIQUE KEY achat_product_achat_id_product_id_unique (achat_id, product_id)
        ");
        DB::statement("
            ALTER TABLE achat_product
            ADD CONSTRAINT achat_product_achat_id_foreign
            FOREIGN KEY (achat_id) REFERENCES achats(id) ON DELETE CASCADE
        ");
        DB::statement("
            ALTER TABLE achat_product
            ADD CONSTRAINT achat_product_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ");
        DB::statement("
            ALTER TABLE achat_product
            ADD CONSTRAINT achat_product_product_variant_id_foreign
            FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
        ");

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};