<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

<<<<<<< HEAD
return Application::configure(basePath: dirname(__DIR__))
=======
$app = Application::configure(basePath: dirname(__DIR__))
>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'storekeeper' => \App\Http\Middleware\StorekeeperMiddleware::class,
            'technician' => \App\Http\Middleware\TechnicianMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
<<<<<<< HEAD
=======

// Manually set the environment to avoid container resolution issues
$app->instance('env', 'local');

return $app;
>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
