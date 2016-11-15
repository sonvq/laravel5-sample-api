<?php
/**
 * 
 * @author 	
 */
namespace App\Common;

use Illuminate\Support\Facades\Input;
use App\Media;
use App\AppMedia;

class Helper
{
    const NOT_FOUND = 'NOT_FOUND';
    const NOT_FOUND_MSG = "Resource Not Found";
    
    const VALIDATION_ERROR = 'UNPROCESSABLE_ENTITY';
    const VALIDATION_ERROR_MSG = 'Unprocessable entity';
    
    const METHOD_NOT_FOUND = 'METHOD_NOT_FOUND';
    const METHOD_NOT_FOUND_MSG = 'Method not implemented yet';
    
    const TOKEN_NOT_PROVIDED = 'TOKEN_NOT_PROVIDED';
    const TOKEN_NOT_PROVIDED_MSG = 'Token not provided';
    
    const API_VERSION_REQUIRED = 'API_VERSION_REQUIRED';
    const API_VERSION_REQUIRED_MSG = 'The api-version is required';
    
    const INVALID_API_VERSION = 'INVALID_API_VERSION';
    const INVALID_API_VERSION_MSG = 'The api-version provided is invalid';
    
    const API_VERSION_NOT_SUPPORTED = 'API_VERSION_NOT_SUPPORTED';
    const API_VERSION_NOT_SUPPORTED_MSG = 'The api-version provided is no longer supported, please upgrade your app';
    
    const INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';
    const INTERNAL_SERVER_ERROR_MSG = 'Internal server error';
        
    const SUCCESS = 'SUCCESS';
    const SUCCESS_MSG = 'Success';
    
    const TOKEN_EXPIRED = 'TOKEN_EXPIRED';
    const TOKEN_EXPIRED_MSG = 'Token expired';
    
    const TOKEN_INVALID = 'TOKEN_INVALID';
    const TOKEN_INVALID_MSG = 'Token invalid';
    
    const TOKEN_BLACKLISTED = 'TOKEN_BLACKLISTED';
    const TOKEN_BLACKLISTED_MSG = 'Token blacklisted';
    
    const TOKEN_ABSENT = 'TOKEN_ABSENT';
    const TOKEN_ABSENT_MSG = 'Token absent';
    
    const USER_NOT_FOUND = 'USER_NOT_FOUND';
    const USER_NOT_FOUND_MSG = 'User not found';
    
    const WRONG_CREDENTIAL = 'WRONG_CREDENTIAL';
    const WRONG_CREDENTIAL_MSG = 'Invalid user id/password';
    
    const WRONG_CREDENTIAL_BLOCKED = 'WRONG_CREDENTIAL_BLOCKED';
    const WRONG_CREDENTIAL_BLOCKED_MSG = 'Your account has been locked, please contact your administrator for supports';
    
    const TOKEN_CREATE_ERROR = 'TOKEN_CREATE_ERROR';
    const TOKEN_CREATE_ERROR_MSG = 'Could not create token';
    
    const ACCOUNT_NOT_CONFIRM = 'ACCOUNT_NOT_CONFIRM';
    const ACCOUNT_NOT_CONFIRM_MSG = 'Your account is not confirmed. Please click the confirmation link in your e-mail';
           
    const ACCOUNT_INACTIVE = 'ACCOUNT_INACTIVE';
    const ACCOUNT_INACTIVE_MSG = 'Your account is not actived. Please contact the administration for more details';
    
    const INVALID_EMAIL = 'INVALID_EMAIL';
    const INVALID_EMAIL_MSG = "We can't find a user with that e-mail address";
    
    const INCORRECT_CURRENT_PASSWORD = 'INCORRECT_CURRENT_PASSWORD';
    const INCORRECT_CURRENT_PASSWORD_MSG = 'Current password is incorrect';
    
    const CHECKLIST_NOT_FOUND = 'CHECKLIST_NOT_FOUND';
    const CHECKLIST_NOT_FOUND_MSG = 'Checklist not found';
    
    const CUSTOMER_NOT_FOUND = 'CUSTOMER_NOT_FOUND';
    const CUSTOMER_NOT_FOUND_MSG = 'Customer not found';

	public static function generate_uuid()
	{
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
    
    public static function timestampSecondsToMilliseconds($timestampSecond) {
        return $timestampSecond . '000';
    }

    public static function getErrorMessageValidation($validator) {       
        
        $validateMessageArray = $validator->messages()->toArray();
        $returnMessageArray = array();

        if (count($validateMessageArray) > 0) {
            foreach ($validateMessageArray as $singleErrorArray) {
                $returnMessageArray = array_merge($returnMessageArray, $singleErrorArray);
            }
        }

        return $returnMessageArray;
    }
    
    public static function responseFormat($messageKey = null, $optionalMessage = null, $result = null) {
        
        return [
            'message_key' => $messageKey,
            'optional_message' => $optionalMessage,
            'result' => $result,
            'server_time' => time(),
        ];
    }
    
    public static function jsonResponse ($messageKey = null, $optionalMessage = null, $result = null, $statusCode = null) {
        return response()->json(Helper::responseFormat($messageKey, $optionalMessage, $result), $statusCode);
    }
    
    public static function okResponse($result = null) {
        return response()->json(Helper::responseFormat(Helper::SUCCESS, null, $result), 200, [], JSON_NUMERIC_CHECK);
    }    
    
    public static function internalServerErrorResponse ($messageKey = null, $optionalMessage = null, $result = null) {
        return response()->json(Helper::responseFormat($messageKey, $optionalMessage, $result), 500);
    }
    
    public static function notFoundErrorResponse ($messageKey = null, $optionalMessage = null, $result = null) {
        return response()->json(Helper::responseFormat($messageKey, $optionalMessage, $result), 404);
    }
    
    public static function validationErrorResponse ($messageKey = null, $optionalMessage = null, $result = null) {
        return response()->json(Helper::responseFormat($messageKey, $optionalMessage, $result), 422);
    }
    
    public static function unauthorizedErrorResponse ($messageKey = null, $optionalMessage = null, $result = null) {
        return response()->json(Helper::responseFormat($messageKey, $optionalMessage, $result), 401);
    }
    
    public static function forbiddenErrorResponse ($messageKey = null, $optionalMessage = null, $result = null) {
        return response()->json(Helper::responseFormat($messageKey, $optionalMessage, $result), 403);
    }
    
    public static function badRequestResponse ($messageKey = null, $optionalMessage = null, $result = null) {
        return response()->json(Helper::responseFormat($messageKey, $optionalMessage, $result), 400);
    }
}

