<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rapport_analyses', function (Blueprint $table) {
            $table->unsignedBigInteger('id_declaration')->nullable()->after('id_laborantin');
        });
    }

    public function down()
    {
        Schema::table('rapport_analyses', function (Blueprint $table) {
            $table->dropColumn('id_declaration');
        });
    }
};
