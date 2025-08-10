<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn(['designation_produit', 'quantiter']);
        });
    }
    public function down()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->string('designation_produit')->nullable();
            $table->integer('quantiter')->nullable();
        });
    }
};

