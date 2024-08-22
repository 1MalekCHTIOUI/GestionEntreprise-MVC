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
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('timbre_fiscale')->nullable();
            $table->string('tva')->nullable();
            $table->string('cachet')->nullable();
            $table->string('logo')->nullable();
            $table->string('titre')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('numero_fiscal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
