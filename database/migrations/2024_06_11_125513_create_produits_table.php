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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('ref')->unique();
            $table->float('prixCharge');
            $table->float('prixVente');
            $table->integer('qte');
            $table->integer('qteMinGros');
            $table->float('prixGros')->nullable();
            $table->integer('promo')->default(0);
            $table->float('longueur')->nullable();
            $table->float('largeur')->nullable();
            $table->float('hauteur')->nullable();
            $table->float('profondeur')->nullable();
            $table->float('tempsProduction');
            $table->text('matiers');
            $table->text('description');
            $table->text('descriptionTechnique');
            $table->string('ficheTechnique')->nullable();
            $table->text('publicationSocial');
            $table->float('fraisTransport');
            $table->unsignedBigInteger('idCategorie');
            $table->string('imagePrincipale')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
