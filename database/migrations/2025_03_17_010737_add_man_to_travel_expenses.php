<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->string('man')->nullable()->after('quantity'); 
            $table->string('man_realisasi')->nullable()->after('man');
        });
    }

    public function down()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->dropColumn('man');
        });
    }
};
