<?php

namespace App\Http\Middleware;

use Closure;
use App\Common\Helper;
/**
 * Class ApiVersion
 * @package App\Http\Middleware
 */
class ApiVersion
{

	/**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $arraySupportedApiVersion = config('api.supported_version');
        $arrayAvailableApiVersion = config('api.available_version');
        
        $apiVersion = app('request')->header('api-version');
        
        if (empty($apiVersion)) {
            return Helper::internalServerErrorResponse(Helper::API_VERSION_REQUIRED, Helper::API_VERSION_REQUIRED_MSG);
        }
        
        if (!in_array($apiVersion, $arrayAvailableApiVersion)) {               
            return Helper::internalServerErrorResponse(Helper::INVALID_API_VERSION, Helper::INVALID_API_VERSION_MSG);        
        }
        
        if (!in_array($apiVersion, $arraySupportedApiVersion)) {               
            return Helper::internalServerErrorResponse(Helper::API_VERSION_NOT_SUPPORTED, Helper::API_VERSION_NOT_SUPPORTED_MSG);
        }
                
        return $next($request);
    }
}