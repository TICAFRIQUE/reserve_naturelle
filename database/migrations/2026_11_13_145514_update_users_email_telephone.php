<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('tel')->nullable(false)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('tel');
        });
    }

    public function down(): void{
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['tel']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('tel')->nullable()->change();
        });
    }
};
