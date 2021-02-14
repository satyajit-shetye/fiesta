<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Playlist;

class PlaylistController extends Controller
{
    function getPlaylists(Request $request){

        $userId = $request->user()->id;

        $playlists = Playlist::where('user_id', $userId)
        ->orderBy('updated_at')
        ->get();

        return response()->json([
            'response' => $playlists
        ]);
    }

    function getPlaylistById(Request $request) {
        $userId = $request->user()->id;

        $playlistId = Route::current()->parameter('id');

        $userId = $request->user()->id;

        $playlists = Playlist::where([
            ['id', '=', $playlistId],
            ['user_id', '=', $userId]
        ])
        ->first();

        return response()->json([
            'response' => $playlists
        ]);
    }

    function addPlaylist(Request $request){
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
 
        $playlist = Playlist::create([
            'name' => $input['name'],
            'description' => array_key_exists('description',$input) ? $input['description'] : null,
            'image_url' => array_key_exists('imageUrl',$input) ? $input['imageUrl'] : null,
            'user_id' => $userId
        ]);

        return response()->json([
            'response' => $playlist
        ]);
    }

    function updatePlaylist(Request $request){

        $userId = $request->user()->id;

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

        $playlist = Playlist::find($input['id']);

        if( is_null($playlist) ){
            return response()->json([
                'error' => 'Playlist not found.'
            ]);    
        }
        
        $playlist->name = $input['name'];
        $playlist->description = $input['description'];
        $playlist->image_url = $input['imageUrl'];
        $playlist->save();

        return response()->json([
            'response' => $playlist
        ]);
    }

    function deletePlaylist(Request $request){
        $userId = $request->user()->id;

        $playlistId = Route::current()->parameter('id');

        $playlist = Playlist::find($playlistId);

        if( is_null($playlist) ){
            return response()->json([
                'error' => 'Playlist not found.'
            ]);    
        }

        Playlist::destroy($playlistId);

        return response()->json([
            'response' => $playlist
        ]);
    }
}
