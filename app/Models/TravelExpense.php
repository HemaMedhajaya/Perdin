<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelExpense extends Model
{
    protected $fillable = [
        'travel_request_id', 
        'transportation', 
        'cost', 
        'quantity', 
        'man', 
        'total', 
        'description',
        'jenis_perjalanan',
        'travel_request_id_realisasi',
        'jenis_perjalanan_realisasi',
        'description_realisasi',
        'cost_realisasi',
        'quantity_realisasi',
        'total_realisasi',
        'transportation_realisasi'
    ];

    public function travelRequest()
    {
        return $this->belongsTo(TravelRequest::class, 'travel_request_id');
    }
    public function travelRequestRealisasi()
    {
        return $this->belongsTo(TravelRequest::class, 'travel_request_id_realisasi');
    }
}
