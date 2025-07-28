<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Cache::get('maintenance_mode')) {
            return response()->view('errors.maintenance', ['message' => 'El sistema está en mantenimiento. Por favor, inténtelo más tarde.']);
        }

        return $next($request);
    }
}
