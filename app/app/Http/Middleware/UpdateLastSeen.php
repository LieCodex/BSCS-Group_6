<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    public function handle($request, Closure $next)
        {
            if (Auth::check()) {
                Auth::user()->update(['last_seen_at' => now()]);
            }

            return $next($request);
        }
}
