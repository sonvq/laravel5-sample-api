<?php

use App\Common\Helper;

Route::group(['middleware' => 'web'], function() {
    /**
     * Switch between the included languages
     */
    Route::group(['namespace' => 'Language'], function () {
        require (__DIR__ . '/Routes/Language/Language.php');
    });

    /**
     * Frontend Routes
     * Namespaces indicate folder structure
     */
    Route::group(['namespace' => 'Frontend'], function () {
        require (__DIR__ . '/Routes/Frontend/Frontend.php');
        require (__DIR__ . '/Routes/Frontend/Access.php');
    });
});

/**
 * Backend Routes
 * Namespaces indicate folder structure
 * Admin middleware groups web, auth, and routeNeedsPermission
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    /**
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    require (__DIR__ . '/Routes/Backend/Dashboard.php');
    require (__DIR__ . '/Routes/Backend/Access.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');
    require (__DIR__ . '/Routes/Backend/CorporateDeck.php');
    require (__DIR__ . '/Routes/Backend/ProductCatalogue.php');
    require (__DIR__ . '/Routes/Backend/Settings.php');
});

    
Route::group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => 'api'], function () {
//    Route::any('/', function() { });

        
    $arrayAvailableApiVersion = config('api.available_version');    
    $apiVersion = app('request')->header('api-version');
    

    if (in_array($apiVersion, $arrayAvailableApiVersion)) {
        require (__DIR__ . '/Routes/Api/V' . $apiVersion . '/Routes.php');
    } else {
        require (__DIR__ . '/Routes/Api/General/Routes.php');
    }
    
    Route::any('/{all}', function ($all) {
        return Helper::notFoundErrorResponse(Helper::NOT_FOUND, Helper::NOT_FOUND_MSG);
    })->where(['all' => '.*']);    
    
}); 

