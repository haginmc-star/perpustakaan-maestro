<?php

use App\Console\Commands\CekBekuanMahasiswa;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'is_super_admin' => \App\Http\Middleware\IsSuperAdmin::class,
        ]);
    })
    ->withSchedule(function ($schedule) {
        $schedule->command(CekBekuanMahasiswa::class)->dailyAt('00:05');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();