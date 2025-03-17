<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->tinyInteger('status_approve')->default(0)->comment('0: Diproses, 1: Disetujui, 2: Ditolak')->change();
        });
    }

    public function down()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->tinyInteger('status_approve')->comment('0: Diproses, 1: Disetujui, 2: Ditolak')->change();
        });
    }
};
