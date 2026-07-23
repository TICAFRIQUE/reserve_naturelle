<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN statut ENUM('panier_converti', 'en_attente', 'payee', 'validee', 'en_livraison', 'livree', 'annulee') NOT NULL DEFAULT 'panier_converti'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN statut ENUM('panier_converti', 'en_attente', 'payee', 'validee', 'livree', 'annulee') NOT NULL DEFAULT 'panier_converti'");
    }
};