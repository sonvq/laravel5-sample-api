<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Common\Helper;

/**
 * Class HelloWorldController
 * @package App\Http\Controllers
 */
class HelloWorldController extends Controller
{

    public function index()
    {
        $helloWorld = new \stdClass();
        $helloWorld->message = "Hello World";
        return Helper::okResponse($helloWorld);
    }

}
