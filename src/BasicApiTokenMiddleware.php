<?php

namespace UoGSoE\ApiTokenMiddleware;

use Closure;
use App\ApiToken;

class BasicApiTokenMiddleware
{
    const CODE = 401;
    const MESSAGE = 'Unauthorized';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $services = array_except(func_get_args(), [0,1]);
        if (!$this->authorized($request, $services)) {
            return response()->json(['message' => self::MESSAGE], self::CODE);
        }
        return $next($request);
    }

    /**
     * Checks an incoming token against one in the database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param array $service
     */
    public function authorized($request, $services)
    {
        $passedToken = $this->extractToken($request);
        if (! $passedToken) {
            return false;
        }

        $apiTokens = ApiToken::whereIn('service', $services)->get();
        if ($apiTokens->isEmpty()) {
            return false;
        }

        return !is_null($apiTokens->first(function ($apiToken) use ($passedToken) {
            return \Hash::check($passedToken, $apiToken->token);
        }));
    }

    /**
     * Try to find the api token in the request
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function extractToken($request)
    {
        if ($request->bearerToken()) {
            return $request->bearerToken();
        }

        if ($request->input('api_token')) {
            return $request->input('api_token');
        }

        return null;
    }
}
