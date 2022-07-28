<?php

Route::group(['prefix' => 'coordinator', 'as' => 'coordinator'], function () {
    Route::group(['namespace' => 'Xguard\Coordinator\Http\Controllers',], function () {

        Route::group(['middleware' => ['web']], function () {

            // Setting sessions variables and checking if user is still logged in
            Route::get('/set-sessions', 'AppController@setCoordinatorAppSessionVariables');

            Route::group(['middleware' => ['coordinator_app_role_check']], function () {

                // We'll let vue router handle 404 (it will redirect to dashboard)
                Route::fallback('AppController@getIndex');

                // All view routes are handled by vue router
                Route::get('/', 'AppController@getIndex');

                // Coordinator App Data
                Route::get('/get-role-and-coordinator-id', 'AppController@getRoleAndCoordinatorId');
                Route::get('/get-admin-page-data', 'AppController@getAdminPageData');
                Route::get('/get-coordinator-profile', 'AppController@getCoordinatorProfile');
                Route::get('/get-footer-info', 'AppController@getFooterInfo');

                // Supervisors
                Route::get('/get-supervisors-data', 'SupervisorController@getSupervisorsData')->name('.get-supervisor-data');
                Route::get('/get-user-shift-odometer-images/{userId}/{shift}', 'SupervisorController@getUserShiftOdometerImages');

                // Coordinators
                Route::post('/create-coordinators', 'CoordinatorController@createCoordinators');
                Route::post('/delete-coordinator/{id}', 'CoordinatorController@deleteCoordinator');
                Route::get('/get-coordinators', 'CoordinatorController@getCoordinators');

                //ERP Data
                Route::get('/get-all-users', 'ErpController@getAllUsers');
                Route::get('/get-some-users/{searchTerm}', 'ErpController@getSomeUsers');
                Route::get('/get-all-active-contracts', 'ErpController@getAllActiveContracts');
                Route::get('/get-some-active-contracts/{searchTerm}', 'ErpController@getSomeActiveContracts');

                //Location

                Route::get(
                    '/get-location-pings/{supervisorShiftId}',
                    'LocationPingController@getSupervisorShiftLocationPings'
                )->name('.get-supervisor-shift-location-ping');
            });
        });
    });
});
