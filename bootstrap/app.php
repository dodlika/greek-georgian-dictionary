<?php

// Add this to your bootstrap/app.php file

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserCanManageWords;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register the custom middleware
        $middleware->alias([
            'can.manage.words' => EnsureUserCanManageWords::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();