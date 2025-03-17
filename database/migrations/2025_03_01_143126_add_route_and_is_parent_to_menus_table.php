<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRouteAndIsParentToMenusTable extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Tambahkan kolom route (nullable, karena tidak semua menu memiliki route)
            $table->string('route')->nullable()->after('name');

            // Tambahkan kolom is_parent (boolean, default true)
            $table->boolean('is_parent')->default(true)->after('route');
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Hapus kolom jika migration di-rollback
            $table->dropColumn('route');
            $table->dropColumn('is_parent');
        });
    }
}