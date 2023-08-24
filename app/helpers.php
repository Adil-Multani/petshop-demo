<?php

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Redis;

if ( ! function_exists('apiResponse')) {
    function apiResponse($data = null, $message = null, $statusCode = 200, $success = true)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }
}


/**
 * Generates a JWT token for the user.
 */
if ( ! function_exists('generateToken')) {
    function generateToken(User $user)
    {
        $role       = $user->is_admin ? 'admin' : 'user';
        $expiration = time() + 3600; // 1 hour

        // Create the token payload
        $tokenPayload = [
            'iss'       => url('/'),
            'user_uuid' => $user->uuid,
            'user_role' => $role,
            'exp'       => $expiration
        ];

        // Load the private key for signing
        $privateKey = file_get_contents(base_path('jwtkeys/private_key.pem'));

        // Generate the JWT token
        $token = JWT::encode($tokenPayload, $privateKey, 'RS256');

        return $token;
    }
}

/**
 * Revokes a token by adding it to the blacklist.
 */
if ( ! function_exists('revokeToken')) {
    function revokeToken($token)
    {
        Redis::sadd('token:blacklist', $token);
        Redis::expire('token:blacklist:' . $token, 3600); // 1 hour
    }
}
