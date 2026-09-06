<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (SiteSetting::isMaintenance()) {
            $pesan = SiteSetting::current()->pesan_maintenance;
            return response()->view('errors.maintenance', compact('pesan'), 503);
        }

        return $next($request);
    }
}
