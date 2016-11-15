<?php

Route::group([
    'prefix'     => '',
    'namespace'  => 'V2',
], function() {

	Route::get('/', 'HelloWorldV2Controller@index');
});