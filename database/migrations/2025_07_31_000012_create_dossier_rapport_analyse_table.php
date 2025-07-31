<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dossier_rapport_analyse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dossier');
            $table->unsignedBigInteger('id_rapport');
            $table->timestamps();
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
            $table->foreign('id_rapport')->references('id_rapport')->on('rapport_analyses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_rapport_analyse');
    }
};

