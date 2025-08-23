<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rapport_analyses', function (Blueprint $table) {
            $table->string('statut')->default('en_attente')->after('fichier');
        });
    }

    public function down()
    {
        Schema::table('rapport_analyses', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};
