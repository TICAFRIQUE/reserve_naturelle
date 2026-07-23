<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livreur_id')->constrained('livreurs');
            $table->foreignId('zone_id')->constrained('zones');
            $table->date('date_tournee');
            $table->enum('statut', ['en_cours', 'terminee'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournees');
    }
};