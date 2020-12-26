<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication
Route::group(['prefix' => '/auth'], function () {
    Route::post('/signup', [AuthenticationController::class, 'signup']);
    Route::post('/signin', [AuthenticationController::class, 'signin']);
});

// User
Route::group(['prefix' => '/user'], function () {
    Route::get('/logged-in-user', [UserController::class, 'loggedInUser']);
    Route::put('/update-user-details', [UserController::class, 'updateUserDetails']);
});