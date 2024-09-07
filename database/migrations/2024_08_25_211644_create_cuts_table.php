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
        Schema::create('cuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idProduit')->constrained('produits')->onDelete('cascade');
            $table->decimal('largeur', 8, 2);
            $table->decimal('longueur', 8, 2);
            $table->decimal('epaisseur', 8, 2);
            $table->decimal('perimetre', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuts');
    }
};
