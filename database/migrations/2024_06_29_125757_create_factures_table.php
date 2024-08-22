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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique()->nullable();
            $table->foreignId('idDevis')->constrained('devis')->onDelete('cascade');
            $table->date('date');
            $table->string('status')->default('Not Paid');
            $table->decimal('totalHT', 10, 2);
            $table->decimal('totalTTC', 10, 2);
            $table->decimal('montant_restant', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
