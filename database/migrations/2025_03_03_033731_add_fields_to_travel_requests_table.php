<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTravelRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->string('nomorso')->nullable(); // Field untuk nomor SO
            $table->string('lokasikerja')->nullable(); // Field untuk lokasi kerja
            $table->text('keperluan')->nullable(); // Field untuk keperluan
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->dropColumn('nomorso');
            $table->dropColumn('lokasikerja');
            $table->dropColumn('keperluan');
        });
    }
}
