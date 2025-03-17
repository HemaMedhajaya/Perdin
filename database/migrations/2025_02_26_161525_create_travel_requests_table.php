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
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User yang mengajukan
            $table->date('start_date');
            $table->date('end_date');
            $table->string('destination');
            $table->text('purpose');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_requests');
    }
};
