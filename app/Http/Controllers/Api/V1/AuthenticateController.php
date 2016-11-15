<?php

namespace App\Http\Controllers\Api\V1;

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

use JWTAuth;
use App\Models\Access\User\User;
use App\Common\Helper;

class AuthenticateController extends BaseController {

    public function login(Request $request) {

        $requestVariable = $request->only('user_id', 'password');
        
        $requestVariable['email'] = $requestVariable['user_id'] . config('variables.company_email');
        unset($requestVariable['user_id']);
        
        $credentials = $requestVariable;
        
        try {
            // verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                $user = User::where('email', '=', $credentials['email'])->first();
                
                if ($user) {
                    $isAdmin = false;
                    if (count($user->roles) > 0) {
                        foreach ($user->roles as $singleRole) {
                            if ($singleRole->id == 1) {
                                $isAdmin = true;
                            }
                        }
                    }
                    
                    if (!$isAdmin) {
                        // Increase the number of login fail
                        $currentLoginFail = $user->fail_login_count;
                        if ($user->status == 1) {
                            if ($currentLoginFail < 2) {
                                $user->fail_login_count = $currentLoginFail + 1;
                                if (!$user->save()) {
                                    return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
                                }
                            } else if ($currentLoginFail >= 2) {
                                $user->fail_login_count = 0;
                                $user->status = 0;
                                if (!$user->save()) {
                                    return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
                                }
                                return Helper::unauthorizedErrorResponse(Helper::WRONG_CREDENTIAL_BLOCKED, Helper::WRONG_CREDENTIAL_BLOCKED_MSG);
                            }
                        }
                    }
                }
                return Helper::unauthorizedErrorResponse(Helper::WRONG_CREDENTIAL, Helper::WRONG_CREDENTIAL_MSG);
            }
        } catch (JWTException $e) {
            // something went wrong
            return Helper::internalServerErrorResponse(Helper::TOKEN_CREATE_ERROR, Helper::TOKEN_CREATE_ERROR_MSG);
        }
        // if no errors are encountered we can return a JWT
        $user = User::where('email', '=', $credentials['email'])->first();
        
        if ($user->confirmed == 0) {
            return Helper::forbiddenErrorResponse(Helper::ACCOUNT_NOT_CONFIRM, Helper::ACCOUNT_NOT_CONFIRM_MSG);
        }
        
        if ($user->status == 0) {
            return Helper::forbiddenErrorResponse(Helper::ACCOUNT_INACTIVE, Helper::ACCOUNT_INACTIVE_MSG);
        }
        
        // If successful login, reset fail_login_count to zero
        $user->fail_login_count = 0;
        if (!$user->save()) {
            return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
        }
        
        $returnObject = new \stdClass();
        $returnObject->user = $user->postProcessModel();
        $returnObject->token = $token;
        
        return Helper::okResponse($returnObject);
    }

    // somewhere in your controller
    public function getAuthenticatedUser() {
        
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
    
    public function forgot(Request $request) {
        $input = $request->all();
        $userId = isset($input['user_id']) ? ($input['user_id']) : '';

        $email = $userId . config('variables.company_email');
        $response = Password::sendResetLink(array('email' => $email), function (Message $message) {
            $message->subject(trans('strings.emails.auth.password_reset_subject'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return Helper::okResponse();

            case Password::INVALID_USER:
                return Helper::unauthorizedErrorResponse(Helper::INVALID_EMAIL, Helper::INVALID_EMAIL_MSG);
        }
    }
    
    // Logout user from system
    public function logout() {
        
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return Helper::notFoundErrorResponse(Helper::USER_NOT_FOUND, Helper::USER_NOT_FOUND_MSG);
            }
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (TokenExpiredException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_EXPIRED, Helper::TOKEN_EXPIRED_MSG);            
        } catch (TokenInvalidException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_INVALID, Helper::TOKEN_INVALID_MSG);
        } catch (JWTException $e) {
            return Helper::unauthorizedErrorResponse(Helper::TOKEN_ABSENT, Helper::TOKEN_ABSENT_MSG);
        }

        // the token is valid and we have found the user via the sub claim
        return Helper::okResponse();
    }
    
    public function refresh(Request $request) {
        try {
            $current_token = JWTAuth::getToken();
            if (!$current_token) {
                return Helper::unauthorizedErrorResponse(Helper::TOKEN_ABSENT, Helper::TOKEN_ABSENT_MSG);
            }
            $token = JWTAuth::refresh($current_token);
            return Helper::okResponse(compact('token'));
        } catch (JWTException $e) {
            if ($e instanceof TokenExpiredException) {
                return Helper::unauthorizedErrorResponse(Helper::TOKEN_EXPIRED, Helper::TOKEN_EXPIRED_MSG); 
            } else if ($e instanceof TokenBlacklistedException) {
                return Helper::unauthorizedErrorResponse(Helper::TOKEN_BLACKLISTED, Helper::TOKEN_BLACKLISTED_MSG);
            } else if ($e instanceof TokenInvalidException) {
                return Helper::unauthorizedErrorResponse(Helper::TOKEN_INVALID, Helper::TOKEN_INVALID_MSG);
            } else if ($e instanceof PayloadException) {
                return Helper::unauthorizedErrorResponse(Helper::TOKEN_EXPIRED, Helper::TOKEN_EXPIRED_MSG);
            } else if ($e instanceof JWTException) {
                return Helper::unauthorizedErrorResponse(Helper::TOKEN_INVALID, Helper::TOKEN_INVALID_MSG);
            }
        }
    }

}
