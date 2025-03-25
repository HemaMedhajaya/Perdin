<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_matrix_approval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perdin');
            $table->unsignedBigInteger('id_matrix');
            $table->integer('number');
            $table->unsignedBigInteger('id_user');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_matrix_approval');
    }
};
