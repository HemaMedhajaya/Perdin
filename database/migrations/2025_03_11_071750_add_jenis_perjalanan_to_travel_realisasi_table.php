<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->tinyInteger('jenis_perjalanan')->after('description')->default(1); 
        });
    }

    public function down()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->dropColumn('jenis_perjalanan');
        });
    }
};
