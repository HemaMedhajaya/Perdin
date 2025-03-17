<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travelcategory extends Model
{
    protected $table = 'travel_categorys';
    protected $fillable = ['travel_request_id','category_id'];

    public function category()
    {
        return $this->belongsTo(Categoryproduct::class, 'category_id');
    }

}
