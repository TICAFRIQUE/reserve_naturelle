<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('sous_category_id')
                  ->nullable()
                  ->after('category_id')
                  ->constrained('sous_categories')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['sous_category_id']);
            $table->dropColumn('sous_category_id');
        });
    }
};