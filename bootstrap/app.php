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
    ->withMiddleware(function (Middleware $middleware): void {
    $middleware->prepend(\App\Http\Middleware\CheckUserActive::class);
        
    // Register route middleware aliases (Laravel 12 bootstrap style)
    // allows using ->middleware('role:admin') and similar in controllers/routes
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'api.role' => \App\Http\Middleware\CheckApiRole::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'check.user.active' => \App\Http\Middleware\CheckUserActive::class,
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
