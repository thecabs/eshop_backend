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
        Schema::create('client_cartes', function (Blueprint $table) {

            $table->unsignedInteger('matr')->unique()->primary();
            $table->string("nom", 80);
            $table->tinyInteger("sexe");
            $table->string("dateNaiss", 10);

            $table->unsignedBigInteger("ville_id")->nullable();
            $table->foreign("ville_id")->references("id")->on("villes");

            $table->string("mobile", 15);
            $table->tinyInteger("whatsapp")->default(true);

            $table->integer("point", 0, 1)->default(0);
            $table->decimal("montantTontine", 8, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_cartes');
    }
};
