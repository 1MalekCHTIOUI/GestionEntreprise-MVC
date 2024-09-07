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
            $table->float('prixGros');
            $table->integer('promo')->nullable()->default(0);
            $table->float('longueur')->nullable();
            $table->float('largeur')->nullable();
            $table->float('hauteur')->nullable();
            $table->float('profondeur')->nullable();
            $table->string('couleur')->nullable();
            $table->float('tempsProduction')->nullable();
            $table->text('matiers')->nullable();
            $table->text('description')->nullable();
            $table->text('descriptionTechnique')->nullable();
            $table->string('ficheTechnique')->nullable();
            $table->text('publicationSocial')->nullable();
            $table->float('fraisTransport')->nullable();
            $table->unsignedBigInteger('idCategorie');
            $table->string('imagePrincipale')->nullable();
            $table->boolean('active')->nullable()->default(false);
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
