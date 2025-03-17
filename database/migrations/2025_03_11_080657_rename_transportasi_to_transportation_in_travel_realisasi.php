<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->renameColumn('transportasi', 'transportation');
        });
    }

    public function down()
    {
        Schema::table('travel_realisasi', function (Blueprint $table) {
            $table->renameColumn('transportation', 'transportasi');
        });
    }
};
