<?php

Route::group(['namespace' => 'App\Http\Controllers',], function () {
    Route::group(['prefix' => 'api/coordinator', 'as' => 'coordinator'], function () {
        Route::post('login', 'Auth\LoginController@apiLogin');
        Route::post('forgot', 'Auth\ForgotPasswordController@apiSendResetLinkEmail');
        Route::put('/evaluation/{user}', 'EvaluationController@putUserEvaluation')->where('user', '[0-9]+');
    });
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
        Route::delete('/job-site-visit/{visitId}', 'JobSiteVisitController@delete')->name('.delete-job-site-visit');

        Route::post('/review', 'ReviewController@store')->name('.create-review');
        Route::patch('/review', 'ReviewController@update')->name('.update-review');
        Route::delete('/review/{reviewId}', 'ReviewController@delete')->name('.delete-review');

        Route::get('/get-all-active-contracts', 'ErpController@getAllActiveContracts');
        Route::get('/get-some-active-contracts/{searchTerm}', 'ErpController@getSomeActiveContracts');
        Route::get('/get-on-site-employees/{addressId}/{isPrimaryAddress}', 'ErpController@getOnSiteEmployees')->name('.get-on-site-employees');
    });
});
