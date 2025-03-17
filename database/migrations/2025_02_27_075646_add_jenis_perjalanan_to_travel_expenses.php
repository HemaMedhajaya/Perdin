<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->tinyInteger('jenis_perjalanan')->default(0); // 0 untuk Akomodasi, 1 untuk Transportasi
        });
    }

    public function down()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->dropColumn('jenis_perjalanan');
        });
    }

};
