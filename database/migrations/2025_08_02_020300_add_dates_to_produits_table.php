<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->date('date_fabrication')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('statut', 20)->nullable(); // valide, non passable, etc.
        });
    }
    public function down()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['date_fabrication', 'date_expiration', 'statut']);
        });
    }
};
