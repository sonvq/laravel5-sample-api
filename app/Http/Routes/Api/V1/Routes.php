<?php

Route::group([
    'prefix'     => '',
    'namespace'  => 'V1',
], function() {

	Route::get('/', 'HelloWorldV1Controller@index');
    Route::get('hello', 'HelloWorldV1Controller@index');
    
    Route::post('login-erp', 'AuthenticateController@login');    
    Route::get('forgot', 'AuthenticateController@forgot');
    
    Route::get('token-erp/refresh', 'AuthenticateController@refresh');
    
    Route::group(['middleware' => ['jwt.auth', 'user.active_confirm']], function() {
        
        Route::get('logout-erp', 'AuthenticateController@logout');
        Route::get('authenticate', 'AuthenticateController@getAuthenticatedUser');
        
        Route::group(['namespace' => 'User'], function() {

			Route::get('user/get', 'UserController@profile');

			
			/**
			 * Specific User
			 */
			Route::group(['prefix' => 'user/{user}'], function() {
//				Route::get('mark/{status}', 'UserController@mark')->name('admin.access.user.mark')->where(['status' => '[0,1]']);
//				Route::get('password/change', 'UserController@changePassword')->name('admin.access.user.change-password');
				Route::post('update/profile', 'UserController@updateProfile');
//				Route::get('login-as', 'UserController@loginAs')->name('admin.access.user.login-as');
			});

		});
        
        Route::group(['namespace' => 'CorporateDeck'], function() {
			Route::resource('corporate-deck', 'CorporateDeckController');		
		});
        
        Route::group(['namespace' => 'Category'], function() {
			Route::resource('category', 'CategoryController');		
		});
        
        Route::group(['namespace' => 'Quotation'], function() {
			Route::resource('quotation', 'QuotationController');		
		});

		Route::group(['namespace' => 'Checklist'], function() {
			Route::resource('checklist', 'ChecklistController');	
			Route::post('checklist/update', 'ChecklistController@update');
			Route::get('droplist', 'ChecklistController@getDroplist');					
		});
        
        Route::group(['namespace' => 'Customer'], function() {
			Route::resource('customer', 'CustomerController');	
            Route::post('customer/update', 'CustomerController@update');		
		});
    });
});