<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->unsignedBigInteger('id_controleur')->nullable()->after('user_id');
            // Si tu veux une contrainte de clé étrangère :
            // $table->foreign('id_controleur')->references('id_controleur')->on('controleurs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn('id_controleur');
        });
    }
};

