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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('numFacture')->nullable();
            $table->decimal('montant', 10, 2);
            $table->date('date');
            $table->enum('status', ['open', 'partially_paid', 'paid'])->default('open');
            $table->timestamps();


            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('numFacture')->references('ref')->on('factures')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
