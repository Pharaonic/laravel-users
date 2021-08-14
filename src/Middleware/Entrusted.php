<?php

namespace Pharaonic\Laravel\Users\Middleware;

use Closure;
use Illuminate\Http\Request;

class Entrusted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user || empty($roles) || !$user->entrusted($roles)) return abort(404);

        return $next($request);
    }
}
