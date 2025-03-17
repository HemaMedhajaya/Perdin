<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_request_id')->nullable()->change();
            $table->string('transportation')->nullable()->change();
            $table->decimal('cost', 10, 0)->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->decimal('total', 10, 0)->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_request_id')->nullable(false)->change();
            $table->string('transportation')->nullable(false)->change();
            $table->decimal('cost', 10, 0)->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->change();
            $table->decimal('total', 10, 0)->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
};
