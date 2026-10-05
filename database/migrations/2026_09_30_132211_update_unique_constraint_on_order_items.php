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
              AND TABLE_NAME = 'order_items'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE order_items DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 2. Supprimer l'index unique problématique (s'il existe)
        $indexes = DB::select("SHOW INDEX FROM order_items WHERE Key_name LIKE '%order_id_product_id_unique%'");
        if (!empty($indexes)) {
            DB::statement("ALTER TABLE order_items DROP INDEX `{$indexes[0]->Key_name}`");
        }

        // 3. Recréer les FK
        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT order_items_order_id_foreign
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        ");
        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT order_items_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id)
        ");
        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT order_items_product_variant_id_foreign
            FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
        ");

        // 4. Nouvelle contrainte unique (variante)
        DB::statement("
            ALTER TABLE order_items
            ADD UNIQUE KEY order_items_order_variant_unique (order_id, product_variant_id)
        ");

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement("ALTER TABLE order_items DROP INDEX `order_items_order_variant_unique`");

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'order_items'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE order_items DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        DB::statement("
            ALTER TABLE order_items
            ADD UNIQUE KEY order_items_order_id_product_id_unique (order_id, product_id)
        ");
        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT order_items_order_id_foreign
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        ");
        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT order_items_product_id_foreign
            FOREIGN KEY (product_id) REFERENCES products(id)
        ");
        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT order_items_product_variant_id_foreign
            FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
        ");

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};