<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // INV-2026-08-05-001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('statut', ['en_cours', 'valide', 'annule'])->default('en_cours');
            $table->date('date_inventaire');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaires');
    }
};