<?php

namespace Pharaonic\Laravel\Users\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pharaonic\Laravel\Users\Classes\AgentDetector as AD;

class AgentDetector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app()->instance('Agent', new AD);
        return $next($request);
    }
}
