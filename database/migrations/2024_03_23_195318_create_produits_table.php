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
        Schema::create('produits', function (Blueprint $table) {

            $table->unsignedInteger('codePro')->unique()->primary();
            $table->string("nomPro", 100);
            $table->decimal("prix", 8, 0, true);
            $table->unsignedInteger('qte');
            $table->string("description", 100);
            $table->string("codeArrivage", 250);
            $table->tinyInteger('actif')->default(true);

            $table->unsignedBigInteger('categorie_id')->nullable(true);
            $table->foreign('categorie_id')->references('id')->on('categories');
            $table->decimal("prixAchat", 8, 0, true);
            $table->decimal("pourcentage", 4, 2, true)->default(0);
            $table->tinyInteger('promo')->default(false);

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
