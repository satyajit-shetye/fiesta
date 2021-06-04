<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DashboardCategory;
use App\Models\DashboardPlaylist;
use App\Models\DashboardVideo;
use App\Models\RecentVideo;
use Mockery\Undefined;

class DashboardController extends Controller
{
    // Category
    function addCategory(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $category = DashboardCategory::create([
            'name' => $input['name'],
        ]);

        return response()->json([
            'response' => $category
        ]);
    }

    function updateCategory(Request $request)
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

        $category = DashboardCategory::find($input['id']);

        if (is_null($category)) {
            return response()->json([
                'error' => 'Category not found.'
            ]);
        }

        $category->name = $input['name'];
        $category->save();

        return response()->json([
            'response' => $category
        ]);
    }

    function removeCategory()
    {
        $categoryId = Route::current()->parameter('id');

        $category = DashboardCategory::find($categoryId);

        if (is_null($category)) {
            return response()->json([
                'error' => 'Category not found.'
            ]);
        }

        $category->is_deleted = true;
        $category->save();

        return response()->json([
            'response' => $category
        ]);
    }

    function getCategory()
    {
        $category = DashboardCategory::orderBy('updated_at')
            ->get();

        return response()->json([
            'response' => $category
        ]);
    }

    // Playlist
    function addPlaylist(Request $request)
    {
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
            'dashboard_category_id' => $input['category_id'],
            'name' => $input['name'],
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

    function removePlaylist()
    {
        $playlistId = Route::current()->parameter('id');

        $playlist = DashboardPlaylist::find($playlistId);

        if (is_null($playlist)) {
            return response()->json([
                'error' => 'Playlist not found.'
            ]);
        }

        $playlist->is_deleted = 1;
        $playlist->save();

        return response()->json([
            'response' => $playlist
        ]);
    }

    function getPlaylist()
    {
        $playlist = DashboardPlaylist::orderBy('updated_at')
            ->get();

        return response()->json([
            'response' => $playlist
        ]);
    }

    //Video
    function addVideo(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'dashboard_playlist_id' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $video = DashboardVideo::create([
            'dashboard_playlist_id' => $input['playlist_id'],
            'name' => $input['name'],
            'description' => $input['description'],
            'thumbnail' => $input['thumbnail'],
            'file' => $input['file'],
            'source' => $input['source'],
        ]);

        return response()->json([
            'response' => $video
        ]);
    }

    /*
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

    function removePlaylist()
    {
        $playlistId = Route::current()->parameter('id');

        $playlist = DashboardPlaylist::find($playlistId);

        if (is_null($playlist)) {
            return response()->json([
                'error' => 'Playlist not found.'
            ]);
        }

        $playlist->is_deleted = 1;
        $playlist->save();

        return response()->json([
            'response' => $playlist
        ]);
    }

    function getPlaylist()
    {
        $playlist = DashboardPlaylist::orderBy('updated_at')
            ->get();

        return response()->json([
            'response' => $playlist
        ]);
    }
*/
    function getCategoryWithPlaylist()
    {
        $is_paid = Route::current()->parameter('is_paid');

        if ($is_paid) {
            $categoryList = DashboardCategory::with(['playlists' => function ($query) {
                $query->where('user_id', null)->where('is_paid', 1)->select(['id', 'name', 'dashboard_category_id', 'background_url']);
            }])->select('name', 'id')->where('id', '<>', 3)->get()->toArray();
        } else {
            $categoryList = DashboardCategory::with(['playlists' => function ($query) {
                $query->where('user_id', null)->where('is_paid', 0)->select(['id', 'name', 'dashboard_category_id', 'background_url']);
            }])->select('name', 'id')->where('id', '<>', 3)->get()->toArray();
        }

        foreach ($categoryList as &$category) {
            foreach ($category['playlists'] as &$playlist) {
                $playlist['background_url'] = asset($playlist['background_url']);
            }
        }

        return response()->json([
            'response' => $categoryList
        ]);
    }

    function getVideoByPlaylist()
    {
        $dashboard_playlist_id = Route::current()->parameter('dashboard_playlist_id');

        $videos = DashboardVideo::where('dashboard_playlist_id', '=', $dashboard_playlist_id)->get();

        return response()->json([
            'response' => $videos
        ]);
    }

    function getRelatedVideos()
    {
        $videoId = Route::current()->parameter('video_id');

        $videos = DashboardVideo::where('video_id', '<>', $videoId)
            ->groupBy('video_id')
            ->select('*')
            ->get();

        return response()->json([
            'response' => $videos
        ]);
    }

    function postPlayingNow(Request $request)
    {
        $videoId = Route::current()->parameter('id');

        $video = DashboardVideo::where('id', '=', $videoId)->get();

        if (count($video) === 0) {
            return response()->json([
                'errors' => 'Video does not exists.'
            ]);
        }

        RecentVideo::create([
            'dashboard_video_id' => $videoId,
            'user_id' => $request->user()->id
        ]);

        $videos = DB::table('recent_videos')
            ->where('user_id', '=', $request->user()->id)
            ->join('dashboard_videos', 'dashboard_videos.id', '=', 'recent_videos.dashboard_video_id')
            ->orderBy('recent_videos.id', 'DESC')
            ->select('dashboard_videos.*')
            ->get();

        // NEED BETTER LOGIC
        $videoList = [];
        $videoListIds = [];
        foreach ($videos as $video) {
            if (!isset($videoListIds[$video->video_id])) {
                $videoListIds[$video->video_id] = true;
                array_push($videoList, $video);
            }
        }

        return response()->json([
            'response' => array_slice($videoList, 0, 10)
        ]);
    }

    function getPlayingNow(Request $request)
    {

        $videos = DB::table('recent_videos')
            ->where('user_id', '=', $request->user()->id)
            ->join('dashboard_videos', 'dashboard_videos.id', '=', 'recent_videos.dashboard_video_id')
            ->orderBy('recent_videos.id', 'DESC')
            ->select('dashboard_videos.*')
            ->get();

        // NEED BETTER LOGIC
        $videoList = [];
        $videoListIds = [];
        foreach ($videos as $video) {
            if (!isset($videoListIds[$video->video_id])) {
                $videoListIds[$video->video_id] = true;
                array_push($videoList, $video);
            }
        }

        return response()->json([
            'response' => array_slice($videoList, 0, 10)
        ]);
    }

    function getBanners()
    {
        $banners = Banner::get();

        foreach ($banners as &$banner) {
            $banner['resource_url'] = asset($banner['resource_url']);
            $banner['thumbnail_url'] = $banner['thumbnail_url'] ? asset($banner['thumbnail_url']) : null;
        }

        return response()->json([
            'response' => $banners
        ]);
    }

    function getMostPlayed()
    {
        $videos = DB::table('dashboard_videos')
            ->join('recent_videos', 'dashboard_videos.id', '=', 'recent_videos.dashboard_video_id')
            ->groupBy('dashboard_videos.video_id')
            ->selectRaw('dashboard_videos.id, dashboard_videos.video_id, dashboard_videos.name, dashboard_videos.description, dashboard_videos.thumbnail, dashboard_videos.file, dashboard_videos.source, COUNT(dashboard_videos.video_id) as play_count')
            ->orderBy('play_count', 'DESC')
            ->get();

        return response()->json([
            'response' => $videos
        ]);
    }
}
