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
        Schema::create('achat_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('lienFacture', 250);
            $table->decimal('montantFac', 10, 2);
            $table->decimal('montantCargo', 10, 2);
            $table->decimal('totalKg', 8, 2);
            $table->decimal('montantGlobal', 10, 2);
            $table->unsignedBigInteger("fournisseur_id");
            $table->foreign("fournisseur_id")->references("id")->on("fournisseurs");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achat_fournisseurs');
    }
};
