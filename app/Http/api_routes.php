<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all API routes are defined.
|
*/

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {

        // Home
        Route::post('home', 'HomeAPIController@showHome');

        // Timeline
        Route::post('timeline', 'TimelineAPIController@showTimeline');

        // User
        Route::post('users', 'UserAPIController@registerUser');

        Route::group(["middleware" => "auth.basic"], function () {
            // Places
            Route::get('places/{place}', 'PlaceAPIController@showPlace');

            // Available Appointments
            Route::get('places/{place}/appointments', 'PlaceAPIController@showAvailableAppointments');

            // Make an Appointment
            Route::post('appointments', 'AppointmentAPIController@makeAppointment');

            // Cancel an Appointment
            Route::delete('appointments/{appointment}', 'AppointmentAPIController@deleteAppointment');

            // detail an appointment
            Route::get('appointments/{appointment}', 'AppointmentAPIController@detailAppointment');

            // List appointments
            Route::get('appointments', 'AppointmentAPIController@listAppointments');

            // Update user
            Route::post("users/{user}", "UserAPIController@updateUser");

            // Show user
            Route::get("users/{user}", "UserAPIController@showUser");
        });

        Route::post("login", "UserAPIController@loginUser");
    });
});

// Image Uploader
Route::post("upload/image", "UploadController@postImage");