<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->string('nomortlp')->after('name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn('nomortlp');
        });
    }
};
