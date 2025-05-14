<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Session\Store as SessionStore;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // SAFELY log session status (optional)
        if (method_exists($request, 'hasSession') && $request->hasSession()) {
            $session = $request->getSession();
            if ($session && $session->isStarted()) {
                logger('Session is available and started');
            } else {
                logger('Session not started yet');
            }
        } else {
            logger('Session store not set yet');
        }

        // Handle auth guards
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }

}
