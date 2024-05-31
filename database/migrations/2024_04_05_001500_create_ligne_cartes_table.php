<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ligne_cartes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("facture_id");
            $table->foreign("facture_id")->references("id")->on("factures")->cascadeOnDelete();
            $table->unsignedInteger("client_Carte_matr");
            $table->foreign("client_Carte_matr")->references("matr")->on("client_cartes");

            $table->integer("point");
            $table->decimal("montantFac", 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_cartes');
    }
};
