<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaylistController;

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
    Route::post('/signin-with-provider', [AuthenticationController::class, 'signinWithProvider']);
});

// User
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => '/auth'], function () {
        Route::get('/logout', [AuthenticationController::class, 'logout']);
    });

    Route::group(['prefix' => '/user'], function () {
        Route::get('/logged-in-user', [UserController::class, 'loggedInUser']);
        Route::put('/update-user-details', [UserController::class, 'updateUserDetails']);
    });

    Route::group(['prefix' => '/playlist'], function () {
        Route::get('/', [PlaylistController::class, 'getPlaylists']);
        Route::delete('/{id}', [PlaylistController::class, 'deletePlaylist']);
        Route::get('/{id}', [PlaylistController::class, 'getPlaylistById']);
        Route::post('/', [PlaylistController::class, 'addPlaylist']);
        Route::put('/', [PlaylistController::class, 'updatePlaylist']);
        
    });
});

Route::any('/unauthorize', function (Request $request) {
    return response()->json([
        'errors' => [
            'user' => 'Unauthorized user.'
        ]
    ], 401);
})->name('unauthorize');
