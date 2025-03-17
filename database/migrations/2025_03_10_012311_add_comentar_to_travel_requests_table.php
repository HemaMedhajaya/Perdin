<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->text('comentar')->nullable()->after('updated_at'); // Tambah field setelah 'updated_at'
        });
    }

    public function down()
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->dropColumn('comentar'); // Hapus field jika rollback
        });
    }
};
