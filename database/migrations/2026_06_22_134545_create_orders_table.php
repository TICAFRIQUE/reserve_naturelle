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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('num_order')->unique();
            $table->integer('mt_total');
            $table->date('date_order');
            $table->enum('statut', ['en_attente', 'payee', 'validee', 'livree', 'annulee'])->default('en_attente');
            $table->enum('remise', ['pourcentage', 'valeur'])->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('adresse_precise')->nullable();
            $table->foreignId('zone_id')->nullable()->constrained('zones');
            $table->string('ville_expedition')->nullable();
            $table->decimal('tarif_livraison', 10, 2)->nullable();
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};