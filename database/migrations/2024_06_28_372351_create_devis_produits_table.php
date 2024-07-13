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
        Schema::create('devis_produits', function (Blueprint $table) {

            $table->id();
            $table->foreignId('idDevis')->constrained('devis')->onDelete('cascade');
            $table->foreignId('idProduit')->constrained('produits')->onDelete('cascade');
            $table->integer('qte');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis_produits');
    }
};
