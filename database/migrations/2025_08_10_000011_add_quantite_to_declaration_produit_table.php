<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('declaration_produit', function (Blueprint $table) {
            $table->integer('quantite')->default(1);
        });
    }
    public function down()
    {
        Schema::table('declaration_produit', function (Blueprint $table) {
            $table->dropColumn('quantite');
        });
    }
};

