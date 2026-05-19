<?php

namespace App\Http\Middleware;

use App\Models\OfficeIp;
use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class ValidIpMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $officeIps = OfficeIp::pluck('ip_address')->toArray();
        $userIp = $request->ip(); // better than request()
        $type = $request->input('punchin_type'); // safer way
        if (!in_array($userIp, $officeIps, true)) {
            return response()->json([
                'status' => false,
                'message' => 'Mark-in / Mark-out allowed only from office network',
            ], 201);
        }
        return $next($request);
    }
}