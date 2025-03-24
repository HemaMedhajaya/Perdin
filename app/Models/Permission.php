<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'groupby',
        'submenu_id'
    ];

    public function submenu()
    {
        return $this->belongsTo(SubMenu::class, 'submenu_id');
    }

    static public function permissiongroup($groupby)
    {
        return Permission::where('groupby', $groupby)->get();
    }
}
