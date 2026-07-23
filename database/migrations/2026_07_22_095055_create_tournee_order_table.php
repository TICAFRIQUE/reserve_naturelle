<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournee_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournee_id')->constrained('tournees')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['tournee_id', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournee_order');
    }
};