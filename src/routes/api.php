<?php

Route::group(['namespace' => 'Xguard\Coordinator\Http\Controllers',], function () {
    Route::group(['prefix' => 'api/coordinator', 'as' => 'coordinator'], function () {
        Route::get('/shift', 'SupervisorShiftController@store')->name('.create-shift');

        Route::post('/shift', 'SupervisorShiftController@store')->name('.create-shift');
        Route::patch('/shift{id}', 'SupervisorShiftController@update')->name('.update-shift');
        Route::post('/odometer', 'OdometerController@store')->name('.create-odometer');
        Route::patch('/odometer{id}', 'OdometerController@update')->name('.update-odometer');
    });
});
