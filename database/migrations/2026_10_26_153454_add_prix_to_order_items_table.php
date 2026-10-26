<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('prix_unitaire', 12, 2)->default(0)->after('qte');
            $table->decimal('sous_total', 12, 2)->default(0)->after('prix_unitaire');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['prix_unitaire', 'sous_total']);
        });
    }
};