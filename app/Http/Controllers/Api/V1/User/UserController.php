<?php

namespace App\Http\Controllers\Api\V1\User;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\PayloadException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

use JWTAuth;
use App\Models\Access\User\User;
use App\Common\Helper;

class UserController extends BaseController {   

    // somewhere in your controller
    public function profile() {
        
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return Helper::notFoundErrorResponse(Helper::USER_NOT_FOUND, Helper::USER_NOT_FOUND_MSG);
            }
        } catch (TokenExpiredException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_EXPIRED, Helper::TOKEN_EXPIRED_MSG);            
        } catch (TokenInvalidException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_INVALID, Helper::TOKEN_INVALID_MSG);
        } catch (JWTException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_ABSENT, Helper::TOKEN_ABSENT_MSG);
        }

        $user = $user->postProcessModel();
        
        // the token is valid and we have found the user via the sub claim
        return Helper::okResponse($user);
    }  
    
    public function updateProfile(Request $request) {
        $input = $request->all();        

        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return Helper::notFoundErrorResponse(Helper::USER_NOT_FOUND, Helper::USER_NOT_FOUND_MSG);
        }

        $validator = Validator::make($input, User::getUpdateProfileRules($input, $user->password));    
        
        if ($validator->passes()) {
            if (isset($input['password']) && !empty($input['password'])) {
                if (!isset($input['current_password']) || empty($input['current_password']) || (Hash::check($input['current_password'], $user->password) == false)) {
                    return Helper::unauthorizedErrorResponse(Helper::INCORRECT_CURRENT_PASSWORD, Helper::INCORRECT_CURRENT_PASSWORD_MSG);
                }
                
                $user->password = bcrypt($input['password']);              
            }
            
            if (!$user->save()) {
                return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
            }   
            
        } else {
            return Helper::validationErrorResponse(Helper::VALIDATION_ERROR, Helper::VALIDATION_ERROR_MSG, $validator->messages()->toArray());
        }
                  
        $user = $user->postProcessModel();

        return Helper::okResponse($user);
    }

}
