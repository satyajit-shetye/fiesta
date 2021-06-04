<?php

namespace App\Http\Controllers;

use App\Models\DashboardPlaylist;
use App\Models\DashboardVideo;
use App\Models\RecentVideo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    function getPlaylists(Request $request)
    {
        $userId = $request->user()->id;

        $playlists = DashboardPlaylist::where('user_id', $userId)
            ->orderBy('updated_at')
            ->get();

        foreach ($playlists as &$playlist) {
            $playlist['background_url'] = asset($playlist['background_url']);
        }

        return response()->json([
            'response' => $playlists
        ]);
    }

    function getPlaylistById(Request $request)
    {
        $userId = $request->user()->id;

        $playlistId = Route::current()->parameter('id');

        $userId = $request->user()->id;

        $playlists = DashboardPlaylist::where([
            ['id', '=', $playlistId],
            ['user_id', '=', $userId]
        ])
            ->first();

        return response()->json([
            'response' => $playlists
        ]);
    }

    function addPlaylist(Request $request)
    {
        $userId = $request->user()->id;

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $playlist = DashboardPlaylist::create([
            'name' => $input['name'],
            'user_id' => $userId,
            'dashboard_category_id' => 3
        ]);

        return response()->json([
            'response' => $playlist
        ]);
    }

    function updatePlaylist(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $playlist = DashboardPlaylist::find($input['id']);

        if (is_null($playlist)) {
            return response()->json([
                'error' => 'Playlist not found.'
            ]);
        }

        $playlist->name = $input['name'];
        $playlist->save();

        return response()->json([
            'response' => $playlist
        ]);
    }

    function deletePlaylist(Request $request)
    {
        $playlistId = Route::current()->parameter('id');

        $playlist = DashboardPlaylist::find($playlistId);

        if (is_null($playlist)) {
            return response()->json([
                'error' => 'Playlist not found.'
            ]);
        }

        $videoList = DashboardVideo::where('dashboard_playlist_id', '=', $playlistId)
        ->get();

        foreach($videoList as $video){
            RecentVideo::where('dashboard_video_id', '=', $video['id'])
            ->delete();
        }

        DashboardVideo::where('dashboard_playlist_id', '=', $playlistId)
            ->delete();

        DashboardPlaylist::destroy($playlistId);

        return response()->json([
            'response' => $playlist
        ]);
    }
}
