<?php


Route::group(['prefix' => 'api/coordinator', 'as' => 'coordinator'], function () {
    Route::post('login', 'Auth\LoginController@apiLogin');
});


Route::group(['namespace' => 'Xguard\Coordinator\Http\Controllers',], function () {
    Route::group(['prefix' => 'api/coordinator', 'as' => 'coordinator'], function () {

        Route::post('/shift', 'SupervisorShiftController@store')->name('.create-shift');
        Route::patch('/shift', 'SupervisorShiftController@update')->name('.update-shift');

        Route::post('/odometer', 'OdometerController@store')->name('.create-odometer');
        Route::patch('/odometer', 'OdometerController@update')->name('.update-odometer');

        Route::post('/stop', 'StopController@store')->name('.create-stop');

        Route::post('/job-site-visit', 'JobSiteVisitController@store')->name('.create-job-site-visit');
        Route::patch('/job-site-visit', 'JobSiteVisitController@update')->name('.update-job-site-visit');
    });
});
