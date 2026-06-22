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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('reference_prod')->unique();
            $table->string('designation');
            $table->text('description')->nullable();
            $table->integer('prix_vente');
            $table->integer('qte_dispo')->default(0);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};