<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dossier_declaration', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dossier');
            $table->unsignedBigInteger('id_declaration');
            $table->timestamps();
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
            $table->foreign('id_declaration')->references('id_declaration')->on('declarations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_declaration');
    }
};

