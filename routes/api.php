<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


//login
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //logout
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    //company
    Route::get('/company', [App\Http\Controllers\Api\CompanyController::class, 'show']);

    //checkin
    Route::post('/checkin', [App\Http\Controllers\Api\AttendanceController::class, 'checkin']);

    //checkout
    Route::post('/checkout', [App\Http\Controllers\Api\AttendanceController::class, 'checkout']);

    //is checkin
    Route::get('/is-checkin', [App\Http\Controllers\Api\AttendanceController::class, 'isCheckedin']);

    //history
    Route::get('/history-attendances', [App\Http\Controllers\Api\AttendanceController::class, 'getHistory']);

    //update profile
    Route::post('/update-profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile']);

    //create permission
    Route::apiResource('/api-permissions', App\Http\Controllers\Api\PermissionController::class);

    //notes
    Route::apiResource('/api-notes', App\Http\Controllers\Api\NoteController::class);

    Route::post('/update-fcm-token', [App\Http\Controllers\Api\AuthController::class, 'updateFcmToken']);
});
