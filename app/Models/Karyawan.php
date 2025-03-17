<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'karyawans'; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'jabatan_id',
        'departement_id',
        'user_id',
        'nomortlp',
        'nik',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function Departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
