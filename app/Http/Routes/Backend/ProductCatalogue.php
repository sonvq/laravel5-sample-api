<?php


	/**
	 * Product catalogue Management
	 */


    Route::resource('product-catalogue', 'ProductCatalogueController', ['except' => ['show']]);

    /**
     * For DataTables
     */
    Route::get('product-catalogue/get', 'ProductCatalogueController@get')->name('admin.product-catalogue.get');

