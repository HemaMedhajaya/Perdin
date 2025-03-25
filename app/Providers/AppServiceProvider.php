<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
use App\Models\SubMenu;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $role = session('role');

            $type = 0;
            if ($role === 1) {
                $type = 1; 
            } elseif ($role === 2) {
                $type = 2; 
            } elseif ($role === 3) {
                $type = 0;
            }

            // Ambil menu utama berdasarkan type
            $menus = Menu::with(['subMenus' => function ($query) {
                $query->where('type_menu', '!=', 0); // Ambil submenus yang bukan menu tunggal
            }])->where('type', $type)->get();

            // Ambil subMenus dengan type_menu = 0 sebagai menu tunggal
            $singleMenus = SubMenu::where('type_menu', 0)
                ->where('type', $type)
                ->get();

            // Share data ke view
            $view->with([
                'menus' => $menus,
                'singleMenus' => $singleMenus,
            ]);
        });
    }
}
