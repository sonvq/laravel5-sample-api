<?php

namespace App\Http\Middleware;

use App\Http\Middleware\BaseJWTMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Common\Helper;
use JWTAuth;

class CheckUserStatusFromToken
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
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return Helper::notFoundErrorResponse(Helper::USER_NOT_FOUND, Helper::USER_NOT_FOUND_MSG);
            }
            
            if ($user->confirmed == 0) {
                return Helper::forbiddenErrorResponse(Helper::ACCOUNT_NOT_CONFIRM, Helper::ACCOUNT_NOT_CONFIRM_MSG);
            }
            
            if ($user->status == 0) {
                return Helper::forbiddenErrorResponse(Helper::ACCOUNT_INACTIVE, Helper::ACCOUNT_INACTIVE_MSG);
            }
        
        } catch (TokenExpiredException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_EXPIRED, Helper::TOKEN_EXPIRED_MSG);            
        } catch (TokenInvalidException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_INVALID, Helper::TOKEN_INVALID_MSG);
        } catch (JWTException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_ABSENT, Helper::TOKEN_ABSENT_MSG);
        }      

        return $next($request);
    }
}
