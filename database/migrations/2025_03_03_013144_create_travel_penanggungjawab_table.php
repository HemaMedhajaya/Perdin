<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_penanggungjawab', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_request_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_penanggungjawab');
    }
};
