<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Remove CORS headers
        $response = $next($request);

        // Remove Access-Control-Allow-Origin header
        $response->header('Access-Control-Allow-Origin', null);

        // Remove Access-Control-Allow-Headers header
        $response->header('Access-Control-Allow-Headers', null);

        return $response;
    }
}
