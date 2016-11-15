<?php


	/**
	 * Corporate Deck Management
	 */


    Route::resource('corporate-deck', 'CorporateDeckController', ['except' => ['show']]);

    /**
     * For DataTables
     */
    Route::get('corporate-deck/get', 'CorporateDeckController@get')->name('admin.corporate-deck.get');

