<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->decimal('cost', 10, 0)->change();
        });
    }

    public function down()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            // Sesuaikan tipe data sebelumnya jika rollback diperlukan
            $table->integer('cost')->change();
        });
    }
};
