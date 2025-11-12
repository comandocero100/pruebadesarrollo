<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class TokenAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !preg_match('/^Bearer\s+(\S+)$/i', $authHeader, $m)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $m[1];
        $user = User::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        auth()->setUser($user);
        return $next($request);
    }
}

