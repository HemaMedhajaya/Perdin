<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TravelRequest extends Model
{
    protected $fillable = ['user_id', 'start_date', 'end_date', 'destination', 'purpose', 'name_project','nomorso','lokasikerja','keperluan','status_approve','comentar'];

    public function participants()
    {
        return $this->hasMany(TravelParticipant::class);
    }
    public function penanggungjawab()
    {
        return $this->hasOne(TravelPenanggungjawab::class);
    }

    public function expenses()
    {
        return $this->hasMany(TravelExpense::class);
    }

    public function categorypf()
    {
        return $this->hasMany(Travelcategory::class)->with('category');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'user_id', 'user_id');
    }
    
}
