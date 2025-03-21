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
        Schema::create('matrix_approval', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('approval_type');
            $table->unsignedBigInteger('udf1');
            $table->unsignedBigInteger('udf2');
            $table->unsignedBigInteger('udf3');
            $table->unsignedBigInteger('udf4');
            $table->unsignedBigInteger('udf5');
            $table->unsignedBigInteger('udf6');
            $table->unsignedBigInteger('udf7');
            $table->unsignedBigInteger('udf8');
            $table->unsignedBigInteger('udf9');
            $table->unsignedBigInteger('udf10');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrix_approval');
    }
};
