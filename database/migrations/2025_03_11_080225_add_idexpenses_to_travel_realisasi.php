<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->unsignedBigInteger('idexpenses')->nullable()->after('travel_request_id'); // Sesuaikan posisi kolom
        });
    }

    public function down()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->dropColumn('idexpenses');
        });
    }
};
