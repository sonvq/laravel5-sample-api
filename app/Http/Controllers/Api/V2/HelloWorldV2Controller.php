<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Common\Helper;

/**
 * Class TestApiController
 * @package App\Http\Controllers
 */
class HelloWorldV2Controller extends Controller
{

    public function index()
    {
        $helloWorld = new \stdClass();
        $helloWorld->message = "Hello World From API V2";
        return Helper::okResponse($helloWorld);
    }

}
