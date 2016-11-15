<?php


	/**
	 * Category Management
	 */


    Route::resource('category', 'CategoryController', ['except' => ['show']]);

    /**
     * For DataTables
     */
    Route::get('category/get', 'CategoryController@get')->name('admin.category.get');
