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
        Schema::create('ajustements', function (Blueprint $table) {
            $table->id();
            $table->integer('qte_corriger');
            $table->integer('qte_apres');
            $table->integer('qte_avant');
            $table->date('date_ajust');
            $table->time('h_ajust');
            $table->text('description')->nullable();
            $table->enum('type', ['ajout', 'retrait'])->default('ajout');
            $table->string('motif');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achat_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajustements');
    }
};