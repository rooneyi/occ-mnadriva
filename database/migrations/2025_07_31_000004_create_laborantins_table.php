<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laborantins', function (Blueprint $table) {
            $table->id('id_laborantin');
            $table->unsignedBigInteger('id_utilisateur');
            $table->timestamps();
            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laborantins');
    }
};

