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
        $middleware->alias([
            'staff'        => \App\Http\Middleware\EnsureStaffAuth::class,
            'alumno'       => \App\Http\Middleware\EnsureAlumnoAuth::class,
            'permiso'      => \App\Http\Middleware\EnsurePermiso::class,
            'staff.ctx'    => \App\Http\Middleware\InjectStaffContext::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
