<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthToken
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
        // Get the token from the Authorization header
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Token not provided'], 401);
        }

        // Validate the token and get the authenticated user
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        // Attach the authenticated user to the request
        $request->attributes->set('authenticatedUser', $user);

        return $next($request);
    }
}
