<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventaire_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('qte');
            $table->enum('type_mvt', ['entree', 'sortie'])->default('entree');
            $table->date('date_mvt');
            $table->time('h_mvt');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaire_product');
    }
};