<?php

namespace Pharaonic\Laravel\Users\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermittedAny
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$permissions)
    {
        $user = $request->user();
        if (!$user || empty($permissions) || !$user->permittedAny($permissions)) return abort(404);

        return $next($request);
    }
}
