<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class TravelPenanggungjawab extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'travel_penanggungjawab';

    protected $fillable = [
        'travel_request_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
