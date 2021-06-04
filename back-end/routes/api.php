<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VideoController;

// GUEST
Route::group(['prefix' => '/auth'], function () {
    Route::post('/signup', [AuthenticationController::class, 'signup']);
    Route::post('/signin', [AuthenticationController::class, 'signin']);
    Route::post('/signin-with-provider', [AuthenticationController::class, 'signinWithProvider']);
});

// LOGGER IN USER
Route::group(['middleware' => ['auth:sanctum']], function () {

    // AUTHENTICATION
    Route::group(['prefix' => '/auth'], function () {
        Route::get('/logout', [AuthenticationController::class, 'logout']);
    });

    // USER
    Route::group(['prefix' => '/user'], function () {
        Route::get('/logged-in-user', [UserController::class, 'loggedInUser']);
        Route::put('/update-user-details', [UserController::class, 'updateUserDetails']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
    });

    // PLAYLIST
    Route::group(['prefix' => '/playlist'], function () {
        Route::get('/get-all', [PlaylistController::class, 'getPlaylists']);
        Route::delete('/{id}', [PlaylistController::class, 'deletePlaylist']);
        Route::get('/{id}', [PlaylistController::class, 'getPlaylistById']);
        Route::post('/', [PlaylistController::class, 'addPlaylist']);
        Route::put('/', [PlaylistController::class, 'updatePlaylist']);
    });

    // SUBSCRIPTION
    Route::group(['prefix' => '/subscription'], function () {
        Route::get('/is-subscribed', [SubscriptionController::class, 'isSubscribed']);
        Route::get('/revoke', [SubscriptionController::class, 'revoke']);
        Route::post('/', [SubscriptionController::class, 'subscribe']);
    });

    // DASHBOARD
    Route::group(['prefix' => '/dashboard'], function () {
        Route::get('/category', [DashboardController::class, 'getCategory']);
        Route::post('/category', [DashboardController::class, 'addCategory']);
        Route::put('/category', [DashboardController::class, 'updateCategory']);
        Route::delete('/category/{id}', [DashboardController::class, 'removeCategory']);

        Route::get('/playlist', [DashboardController::class, 'getPlaylist']);
        Route::post('/playlist', [DashboardController::class, 'addPlaylist']);
        Route::put('/playlist', [DashboardController::class, 'updatePlaylist']);
        Route::delete('/playlist/{id}', [DashboardController::class, 'removePlaylist']);

        Route::get('/video', [DashboardController::class, 'getVideo']);
        Route::get('/video/{dashboard_playlist_id}', [DashboardController::class, 'getVideoByPlaylist']);
        Route::post('/video', [DashboardController::class, 'addVideo']);
        Route::put('/video', [DashboardController::class, 'updateVideo']);
        Route::delete('/video/{id}', [DashboardController::class, 'removeVideo']);

        Route::get('/category-playlist/{is_paid}', [DashboardController::class, 'getCategoryWithPlaylist']);
        Route::get('/related-videos/{video_id}', [DashboardController::class, 'getRelatedVideos']);

        Route::post('/playing-now/{id}', [DashboardController::class, 'postPlayingNow']);
        Route::get('/playing-now', [DashboardController::class, 'getPlayingNow']);

        Route::get('/most-played', [DashboardController::class, 'getMostPlayed']);

        Route::get('/banner', [DashboardController::class, 'getBanners']);
    });

    Route::group(['prefix' => '/video'], function () {
        Route::post('/add-to-playlist', [VideoController::class, 'addToPlaylist']);
        Route::get('/get-by-playlist/{id}', [VideoController::class, 'getByPlaylist']);
        Route::delete('/remove-from-playlist/{id}/{video_id}', [VideoController::class, 'removeFromPlaylist']);
        Route::get('/{search?}', [VideoController::class, 'getVideos']);
    });
});

// HELPER
Route::any('/unauthorize', function (Request $request) {
    return response()->json([
        'errors' => [
            'user' => 'Unauthorized user.'
        ]
    ], 401);
})->name('unauthorize');
