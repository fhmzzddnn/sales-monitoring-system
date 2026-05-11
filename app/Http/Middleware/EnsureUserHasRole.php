<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->roles()->count() === 0) {
            if (!$request->routeIs('dashboard') && !$request->is('logout')) {
                return redirect()->route('dashboard')->with('error', 'Silahkan hubungi administrator untuk mendapatkan akses.');
            }
        }

        return $next($request);
    }
}
