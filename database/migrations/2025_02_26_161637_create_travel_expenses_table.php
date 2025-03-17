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
        Schema::create('travel_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_request_id');
            $table->string('transportation');
            $table->decimal('cost', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_expenses');
    }
};
