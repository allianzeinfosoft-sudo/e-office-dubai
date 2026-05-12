<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectedRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $type = null, $value = null): Response
    {
        // 1. Verify Authentication
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 2. Check Authorization (Role/Permission)
        if ($type === 'role') {
            if (!$user->hasRole($value)) {
                toastr()->error('Permission not allowed', 'Unauthorized');
                return redirect()->route('home');
            }
        } elseif ($type === 'permission') {
            if (!$user->hasPermissionTo($value)) {
                toastr()->error('Permission not allowed', 'Unauthorized');
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
