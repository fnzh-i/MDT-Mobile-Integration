<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class FirebaseAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No token provided.'], 401);
        }

        try {
            // get Google's public keys
            $apiResponse = file_get_contents('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com');
            $publicKeys = json_decode($apiResponse, true);

            // if the string is fake or expired (will throw an error)
            $decoded = JWT::decode($token, $publicKeys);

            //  attach the user's ID to the request for later use if successful
            $request->attributes->add(['firebase_user_id' => $decoded->sub]);

            return $next($request);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Invalid or expired token.',
                'details' => $e->getMessage()
            ], 401);
        }
    }
}
