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
            $table->string('ref')->unique()->nullable();
            $table->date('date')->default(now());
            $table->enum('status', ['done', 'still', 'refused'])->default('still');
            
            $table->decimal('totalHT', 10, 2)->default(0);
            $table->decimal('totalServices', 10, 2)->default(0);
            $table->decimal('totalRemises', 10, 2)->default(0);
            $table->decimal('totalFraisLivraison', 10, 2)->default(0);
            $table->decimal('totalTTC', 10, 2)->default(0);



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
