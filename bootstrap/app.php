<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan middleware sebagai alias
        $middleware->alias([
            'check.expired' => \App\Http\Middleware\CheckExpired::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'jwt.auth' => \App\Http\Middleware\JwtAuthenticate::class,
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
        ]);

        // Atau tambahkan middleware ke grup 'web'
        $middleware->web(append: [
            \App\Http\Middleware\CheckExpired::class,
            // \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi penanganan exception
    })->create();