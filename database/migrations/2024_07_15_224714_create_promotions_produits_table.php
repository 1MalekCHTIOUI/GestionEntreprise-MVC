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
        Schema::create('promotions_produits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPromotion');
            $table->unsignedBigInteger('idProduit');
            // $table->decimal('promo', 5, 2);

            $table->foreign('idPromotion')->references('id')->on('promotions')->onDelete('cascade');
            $table->foreign('idProduit')->references('id')->on('produits')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions_produits');
    }
};
