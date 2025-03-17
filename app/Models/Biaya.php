<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    protected $table = 'biaya';

    protected $fillable = [
        'name',
        'status'
    ];

    // public function getStatusTextAttribute()
    // {
    //     return $this->status == 1 ? 'Tambah' : 'Kurang';
    // }
}
