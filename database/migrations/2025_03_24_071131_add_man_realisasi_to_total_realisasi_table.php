<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->integer('man_realisasi')->nullable()->after('total_realisasi');
        });
    }

    public function down()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->dropColumn('man_realisasi');
        });
    }
};
