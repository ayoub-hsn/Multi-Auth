<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

class CustomSessionHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $sessionId = $request->session()->get('user_' . auth()->id());

        if ($sessionId) {
            $request->session()->setId('user_' . auth()->id() . '_' . $sessionId);
        } else {
            $request->session()->setId('default');
        }

        return $next($request);
    }
}
