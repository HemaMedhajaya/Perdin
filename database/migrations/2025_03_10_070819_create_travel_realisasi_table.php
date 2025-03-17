<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelRealisasiTable extends Migration
{
    public function up()
    {
        Schema::create('travel_realisasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expenses_id'); // Jenis perjalanan (misal: Dinas, Pribadi)
            $table->string('transportasi'); // Deskripsi perjalanan
            $table->decimal('cost', 15, 2); 
            $table->integer('quantity'); 
            $table->decimal('total', 15, 2); 
            $table->text('description'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_realisasi');
    }
}