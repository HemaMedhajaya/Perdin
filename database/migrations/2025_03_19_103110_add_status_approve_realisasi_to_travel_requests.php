<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusApproveRealisasiToTravelRequests extends Migration
{
    /**
     * Menjalankan migrasi untuk menambah kolom status_approve_realisasi
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            // Menambahkan kolom 'status_approve_realisasi'
            $table->integer('status_approve_realisasi')->default(0)->after('status_approve'); 
        });
    }

    /**
     * Membalikkan migrasi untuk menghapus kolom 'status_approve_realisasi'
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            // Menghapus kolom 'status_approve_realisasi'
            $table->dropColumn('status_approve_realisasi');
        });
    }
}
