<?php

Route::group([
    'prefix'     => '',
    'namespace'  => 'General',
], function() {

	Route::get('/', 'HelloWorldController@index');
});