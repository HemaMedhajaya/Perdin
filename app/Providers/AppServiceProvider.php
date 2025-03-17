<?php 
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $role = session('role');

            $type = 0;
            if ($role === 'admin') {
                $type = 1; // Admin
            } elseif ($role === 'adminapprover') {
                $type = 2; // Admin Approver
            }

            $menus = Menu::with('subMenus')
                ->where('type', $type)
                ->get();

            // Share data menu ke view
            $view->with('menus', $menus);
        });
    }
}
