<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        $validUsername = env('VOKATIF_INTERNAL_USERNAME', 'vokatif_internal');
        $validPassword = env('VOKATIF_INTERNAL_PASSWORD', 'vokatif_secret');

        if ($username !== $validUsername || $password !== $validPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Basic Auth tidak valid.',
            ], 401, [
                'WWW-Authenticate' => 'Basic realm="Vokatif Internal API"',
            ]);
        }

        return $next($request);
    }
}
