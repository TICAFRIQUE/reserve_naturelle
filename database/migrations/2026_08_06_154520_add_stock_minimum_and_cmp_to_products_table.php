<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_minimum')->default(0)->after('qte_dispo');
            $table->integer('cmp')->default(0)->after('stock_minimum'); // coût moyen pondéré
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_minimum', 'cmp']);
        });
    }
};