<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->id('id_declaration');
            $table->unsignedBigInteger('user_id');
            $table->string('designation_produit');
            $table->integer('quantiter');
            $table->string('unite');
            $table->string('numero_impot');
            $table->date('date_soumission');
            $table->string('fichier')->nullable();
            $table->string('statut')->default('en_attente');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('declarations');
    }
};

