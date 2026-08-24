<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retours_fournisseur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achat_id')->constrained('achats')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date_retour');
            $table->decimal('montant_avoir', 10, 2)->default(0);
            $table->enum('statut_avoir', [
                'en_attente',
                'rembourse',
                'deduit'
            ])->default('en_attente');
            $table->text('motif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retours_fournisseur');
    }
};