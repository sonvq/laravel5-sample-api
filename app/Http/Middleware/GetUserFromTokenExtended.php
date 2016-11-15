<?php

namespace App\Http\Middleware;

use App\Http\Middleware\BaseJWTMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Common\Helper;

class GetUserFromTokenExtended extends BaseJWTMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', Helper::TOKEN_NOT_PROVIDED, Helper::TOKEN_NOT_PROVIDED_MSG, 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', Helper::TOKEN_EXPIRED, Helper::TOKEN_EXPIRED_MSG, $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', Helper::TOKEN_INVALID, Helper::TOKEN_INVALID_MSG, $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', Helper::USER_NOT_FOUND, Helper::USER_NOT_FOUND_MSG, 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
