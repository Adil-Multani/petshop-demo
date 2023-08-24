<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Redis;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        if ( ! $token) {
            return apiResponse(null, 'Token not provided', 401, false);
        }

        // Remove "Bearer " prefix from the Authorization header
        $token = str_replace('Bearer ', '', $token);

        try {
            $publicKey = file_get_contents(base_path('jwtkeys/public_key.pem'));
            $decoded   = JWT::decode($token, new Key($publicKey, 'RS256'));

            if ($decoded->iss !== url('/')) {
                return apiResponse(null, 'Invalid issuer', 401, false);
            }

            // Verify token expiration
            if ($this->isTokenExpired($decoded)) {
                return apiResponse(null, 'Token has expired', 401, false);
            }

            if ($this->isTokenRevoked($token)) {
                return apiResponse(null, 'Token has been revoked', 401, false);
            }

            $request->user_uuid = $decoded->user_uuid;
            $request->user_role = $decoded->user_role;

            if (isset($request->route()->action['as']) && $request->route()->action['as'] == 'edit') {
                $request->merge([
                    'user_uuid' => $decoded->user_uuid,
                ]);
            }

            return $next($request);
        } catch (Exception $e) {
            return apiResponse(null, $e->getMessage(), 401, false);
        }
    }

    private function isTokenExpired($decoded)
    {
        return time() >= $decoded->exp;
    }

    private function isTokenRevoked($token)
    {
        return Redis::sismember('token:blacklist', $token);
    }
}
