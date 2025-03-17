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
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_request_id_realisasi')->after('description')->nullable();
            $table->string('transportation_realisasi')->after('travel_request_id_realisasi')->nullable();
            $table->decimal('cost_realisasi', 10, 0)->after('transportation_realisasi')->nullable();
            $table->integer('quantity_realisasi')->after('cost_realisasi')->nullable();
            $table->decimal('total_realisasi', 10, 0)->after('quantity_realisasi')->nullable();
            $table->text('description_realisasi')->after('total_realisasi')->nullable();
            $table->tinyInteger('jenis_perjalanan_realisasi')->after('description_realisasi')->default(0);
        });
    }

    /*
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('travel_expenses', 'travel_request_id_realisasi')) {
                $table->dropColumn('travel_request_id_realisasi');
            }
            if (Schema::hasColumn('travel_expenses', 'transportation_realisasi')) {
                $table->dropColumn('transportation_realisasi');
            }
            if (Schema::hasColumn('travel_expenses', 'cost_realisasi')) {
                $table->dropColumn('cost_realisasi');
            }
            if (Schema::hasColumn('travel_expenses', 'quantity_realisasi')) {
                $table->dropColumn('quantity_realisasi');
            }
            if (Schema::hasColumn('travel_expenses', 'total_realisasi')) {
                $table->dropColumn('total_realisasi');
            }
            if (Schema::hasColumn('travel_expenses', 'description_realisasi')) {
                $table->dropColumn('description_realisasi');
            }
            if (Schema::hasColumn('travel_expenses', 'jenis_perjalanan')) {
                $table->dropColumn('jenis_perjalanan');
            }
        });
    }
};
