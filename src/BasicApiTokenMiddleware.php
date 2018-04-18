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
    public function handle($request, Closure $next, $service)
    {
        if (!$this->authorized($request, $service)) {
            return response()->json(['message' => self::MESSAGE], self::CODE);
        }
        return $next($request);
    }

    /**
     * Checks an incoming token against one in the database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $service
     */
    public function authorized($request, $service)
    {
        $passedToken = $this->extractToken($request);
        if (! $passedToken) {
            return false;
        }

        $service = ApiToken::where('service', '=', $service)->first();
        if (! $service) {
            return false;
        }

        if (! \Hash::check($passedToken, $service->token)) {
            return false;
        }

        return true;
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
