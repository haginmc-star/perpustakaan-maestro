<?php

// CATATAN: Ini contoh untuk Laravel 11+. Kalau kamu pakai Laravel 10 ke bawah,
// daftarkan middleware di app/Http/Kernel.php ($routeMiddleware) dan scheduler
// di app/Console/Kernel.php seperti biasa. Lihat README.md untuk detail.

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
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'is_super_admin' => \App\Http\Middleware\IsSuperAdmin::class,
            'check_maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withSchedule(function ($schedule) {
        // Jalankan pengecekan bekuan otomatis setiap hari jam 00:05
        $schedule->command(CekBekuanMahasiswa::class)->dailyAt('00:05');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
