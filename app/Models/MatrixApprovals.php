<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatrixApprovals extends Model
{
    protected $table = 'matrix_approval';

    protected $fillable = [
        'name',
        'approval_type',
        'udf1',
        'udf2',
        'udf3',
        'udf4',
        'udf5',
        'udf6',
        'udf7',
        'udf8',
        'udf9',
        'udf10',
    ];
}
