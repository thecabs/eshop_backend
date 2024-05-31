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
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("commande_id")->nullable();
            $table->foreign("commande_id")->references("id")->on("commandes")->onDelete("cascade");


            $table->unsignedInteger("produit_codePro")->nullable();
            $table->foreign("produit_codePro")->references("codePro")->on("produits");

            $table->unsignedInteger('qte');
            $table->string("taille", 30)->nullable();
            $table->string("couleur", 30)->nullable();

            $table->tinyInteger('disponible')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_commandes');
    }
};
