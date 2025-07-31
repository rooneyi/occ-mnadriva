<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->id('id_declaration');
            $table->unsignedBigInteger('id_client');
            $table->string('produit'); // ou relation avec produits via pivot
            $table->string('unite');
            $table->string('numero_import');
            $table->date('date_soumission');
            $table->string('document')->nullable(); // chemin du document joint
            $table->string('statut')->default('en_attente'); // pour notifications
            $table->timestamps();
            $table->foreign('id_client')->references('id_client')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('declarations');
    }
};

