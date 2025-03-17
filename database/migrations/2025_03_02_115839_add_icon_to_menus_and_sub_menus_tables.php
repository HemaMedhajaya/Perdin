<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconToMenusAndSubMenusTables extends Migration
{
    public function up()
    {
        // Tambahkan kolom icon ke tabel menus
        Schema::table('menus', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('route');
        });

        // Tambahkan kolom icon ke tabel sub_menus
        Schema::table('sub_menus', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('route');
        });
    }

    public function down()
    {
        // Hapus kolom icon dari tabel menus
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        // Hapus kolom icon dari tabel sub_menus
        Schema::table('sub_menus', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
}