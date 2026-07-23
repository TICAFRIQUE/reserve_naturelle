<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void{
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('montant_ttc', 10, 2)
                ->after('tarif_livraison');
        });
    }

    public function down(): void{
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('montant_ttc');
        });
    }
};
