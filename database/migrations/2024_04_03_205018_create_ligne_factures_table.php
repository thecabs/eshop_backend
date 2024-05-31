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
        Schema::create('ligne_factures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("facture_id")->nullable();
            $table->foreign("facture_id")->references("id")->on("factures")->onDelete("cascade");


            $table->unsignedInteger("produit_codePro")->nullable();
            $table->foreign("produit_codePro")->references("codePro")->on("produits");

            $table->unsignedInteger('qte');
            $table->decimal("prix", 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_factures');
    }
};
