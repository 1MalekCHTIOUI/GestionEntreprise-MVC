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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();

            // $table->unsignedBigInteger('idProduit');
            // $table->integer('qte');
            $table->date('date')->default(now());
            $table->date('valid_until');
            $table->boolean('status')->default(false);


            // Foreign keys
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            // $table->foreign('idProduit')->references('id')->on('produits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
