<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMatrixApprovals extends Model
{
    protected $table = 'user_matrix_approval';
    protected $fillable = [
      'id_perdin',
      'id_matrix',
      'number',
      'id_user',
      'status',  
    ];
}
