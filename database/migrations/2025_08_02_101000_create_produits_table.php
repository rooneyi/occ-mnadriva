<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        schema::dropIfExists('produits');
        Schema::create('produits', function (Blueprint $table) {
            $table->id('id_produit');
            $table->string('categorie_produit');
            $table->string('nom_produit');
            $table->text('description')->nullable();
            $table->date('date_fabrication')->nullable();
            $table->date('date_expiration')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }
};

