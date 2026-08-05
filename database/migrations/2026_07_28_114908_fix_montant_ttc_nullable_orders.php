<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void{
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('montant_ttc', 10, 2)->nullable()->default(0)->change();
            });
        }

        public function down(): void{
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('montant_ttc', 10, 2)->nullable(false)->change();
            });
        }
};
