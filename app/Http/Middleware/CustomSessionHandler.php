<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            $sessId = $request->session()->getId();
            $sessionId = DB::table('sessions')->insertGetId([
                'id' => $sessId,
                'user_id' => auth()->id(),
                'ip_address' => $request->getClientIp(),
                'user_agent' => $request->header('User-Agent'),
                'payload' => '',
                'last_activity' => time(),
            ]);
            $request->session()->setId('user_' . auth()->id() . '_' . $sessionId);
            $request->session()->put('user_' . auth()->id(), $sessionId);
        }

        return $next($request);
    }
}
