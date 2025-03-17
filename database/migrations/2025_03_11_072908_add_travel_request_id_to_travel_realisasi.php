<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_request_id')->after('id');
            $table->foreign('travel_request_id')->references('id')->on('travel_requests')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->dropForeign(['travel_request_id']);
            $table->dropColumn('travel_request_id');
        });
    }
};
