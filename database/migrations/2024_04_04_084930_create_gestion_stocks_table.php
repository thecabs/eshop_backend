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
        Schema::create('gestion_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer("qte", 0, 1);
            $table->dateTime("dateStock");
            $table->tinyInteger("operation");

            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users");

            $table->unsignedInteger("produit_codePro")->nullable();
            $table->foreign("produit_codePro")->references("codePro")->on("produits");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestion_stocks');
    }
};
