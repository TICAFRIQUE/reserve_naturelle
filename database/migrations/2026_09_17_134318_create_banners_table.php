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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('titre_span')->nullable();      // partie colorée
            $table->text('sous_titre')->nullable();
            $table->string('texte_bouton')->nullable();
            $table->string('lien_bouton')->nullable();
            $table->string('image_path')->nullable();      // chemin image
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('banners');
    }
};
