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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('nom_societe');
            $table->string('tel1');
            $table->string('tel2')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook_page')->nullable();
            $table->string('instagram_account')->nullable();
            $table->string('linkedin_page')->nullable();
            $table->string('site_web')->nullable();
            $table->string('email');
            $table->foreignId('pays_id')->constrained('countries')->onDelete('cascade');
            $table->foreignId('gouvernerat_id')->constrained('states')->onDelete('cascade');
            $table->string('adresse');
            $table->string('matricul_fiscal');
            $table->string('secteur')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('label_id')->constrained()->onDelete('cascade');
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
