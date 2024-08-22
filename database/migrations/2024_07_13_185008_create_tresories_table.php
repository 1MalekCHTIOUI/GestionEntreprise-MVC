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

        Schema::create('tresories', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 15, 2);
            $table->string('type_paiement');
            $table->date('date');
            $table->string('numFacture')->nullable();
            $table->date('date_cheque')->nullable();
            $table->boolean('paye')->default(false);
            $table->text('notes')->nullable();
            $table->foreign('numFacture')->references('ref')->on('factures')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tresorie');
    }
};
