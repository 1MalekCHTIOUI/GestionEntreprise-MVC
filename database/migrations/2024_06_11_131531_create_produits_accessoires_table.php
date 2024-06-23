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
        Schema::create('produits_accessoires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idProduit');
            $table->unsignedBigInteger('idAccessoire');
            $table->integer('qte');
            $table->timestamps();

            $table->foreign('idProduit')->references('id')->on('produits')->onDelete('cascade');
            $table->foreign('idAccessoire')->references('id')->on('accessoires')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits_accessoires');
    }
};
