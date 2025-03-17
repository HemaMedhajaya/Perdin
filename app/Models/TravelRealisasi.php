<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TravelRealisasi extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'travel_realisasi';

    protected $fillable = ['travel_request_id', 'transportation', 'cost', 'quantity', 'total', 'description','jenis_perjalanan', 'idexpenses'];
}
