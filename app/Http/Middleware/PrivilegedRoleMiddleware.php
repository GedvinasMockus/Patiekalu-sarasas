<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PrivilegedRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = JWTAuth::payload()->get('role');

        if($role == "Admin" || $role == "Owner")
            return $next($request);
        else {
            return response()->json(['message' => "You have insufficient permissions."], 403);
        }
        // return $next($request);
    }
}
