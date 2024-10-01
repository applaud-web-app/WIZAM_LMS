<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next)
    {
        // Validate the token from the Authorization header
        $token = $request->bearerToken();

        if (!$token || !isValid($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

    private function isValid($token)
    {
        // Implement your token validation logic here
        return true; // Replace with actual validation
    }
}
