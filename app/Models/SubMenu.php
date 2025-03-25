<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'route', 'icon', 'menu_id', 'type', 'type_menu'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}