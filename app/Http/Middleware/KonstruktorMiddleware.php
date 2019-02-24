<?php

namespace App\Http\Middleware;

use Closure;

class KonstruktorMiddleware
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
        if(auth()->user()->hasRole('konstruktor'))return $next($request);
        return redirect(auth()->user()->getRedirectUrl());
    }
}

