<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelParticipant extends Model
{
    protected $fillable = ['travel_request_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
