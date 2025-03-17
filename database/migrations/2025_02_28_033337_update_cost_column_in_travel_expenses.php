<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->decimal('cost', 10, 0)->change();
        });
    }

    public function down()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->change(); // Kembalikan ke 10,2 jika perlu
        });
    }
};
