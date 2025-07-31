<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('declaration_produit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_declaration');
            $table->unsignedBigInteger('id_produit');
            $table->timestamps();
            $table->foreign('id_declaration')->references('id_declaration')->on('declarations')->onDelete('cascade');
            $table->foreign('id_produit')->references('id_produit')->on('produits')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('declaration_produit');
    }
};

