<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Common\Helper;

/**
 * Class HelloWorldV1Controller
 * @package App\Http\Controllers
 */
class HelloWorldV1Controller extends Controller
{

    public function index()
    {
        $helloWorld = new \stdClass();
        $helloWorld->message = "Hello World From API V1";
        return Helper::okResponse($helloWorld);
    }

}
