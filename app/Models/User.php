<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;  // Tambahkan import ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject  // Implementasikan JWTSubject
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_ADMIN_APPROVER ='adminapprover';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp_code',
        'otp_expiry',
        'last_login',
        'role',
        'status_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Implementasi metode dari JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); 
    }

    public function getJWTCustomClaims()
    {
        return []; 
    }
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }

}
