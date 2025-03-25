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
        Schema::table('sub_menus', function (Blueprint $table) {
            $table->dropForeign(['menu_id']); 
            $table->integer('menu_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sub_menus', function (Blueprint $table) {
            $table->integer('menu_id')->nullable(false)->change(); 
            $table->foreign('menu_id')->references('id')->on('menus');
        });
    }

};
