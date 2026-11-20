<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('num_reservation')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->unsignedInteger('qte');
            $table->unsignedInteger('prix_unitaire');   // prix figé à la réservation
            $table->unsignedInteger('montant');         // qte * prix_unitaire
            $table->enum('statut', ['validee', 'payee', 'livree', 'annulee'])->default('validee');
            $table->text('note')->nullable();
            $table->date('date_reservation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};