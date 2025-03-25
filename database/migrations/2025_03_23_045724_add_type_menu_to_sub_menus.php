<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sub_menus', function (Blueprint $table) {
            $table->tinyInteger('type_menu')->default(0)->after('type'); 
        });
    }

    public function down(): void
    {
        Schema::table('sub_menus', function (Blueprint $table) {
            $table->dropColumn('type_menu');
        });
    }
};
