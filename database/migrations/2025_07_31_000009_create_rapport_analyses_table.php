<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rapport_analyses', function (Blueprint $table) {
            $table->id('id_rapport');
            $table->unsignedBigInteger('id_laborantin');
            $table->unsignedBigInteger('id_declaration');
            $table->string('code_lab');
            $table->string('designation_produit');
            $table->float('quantite');
            $table->string('methode_essai');
            $table->string('aspect_exterieur');
            $table->string('resultat_analyse');
            $table->date('date_fabrication');
            $table->date('date_expiration');
            $table->string('conclusion');
            $table->timestamps();
            $table->foreign('id_laborantin')->references('id_laborantin')->on('laborantins')->onDelete('cascade');
            $table->foreign('id_declaration')->references('id_declaration')->on('declarations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_analyses');
    }
};

