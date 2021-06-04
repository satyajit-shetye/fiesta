<?php

namespace App\Http\Controllers;

use App\Models\DashboardPlaylist;
use App\Models\DashboardVideo;
use App\Models\RecentVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use App\Models\Subscription;

class VideoController extends Controller
{
    public function getVideos(Request $request)
    {

        $searchText = Route::current()->parameter('search') ? Route::current()->parameter('search') : '';

        //$videoList = $this->getYTVideoList($request, $searchText);

        $videoList = $this->getVideoList($request, $searchText);

        return response()->json([
            'response' => $videoList
        ], 200);
    }

    public function addToPlaylist(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'video_id' => ['required'],
            'playlist_id' => ['required'],
            'name' => ['required'],
            'source' => ['required'],
            'thumbnail' => ['required'],
            'file' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $existingRecord = DashboardVideo::where('dashboard_playlist_id', '=', $input['playlist_id'])
            ->where('video_id', '=', $input['video_id'])
            ->first();

        if ($existingRecord) {
            return response()->json([
                'response' => 'Already exists in selected playlist'
            ], 200);
        }

        $response = DashboardVideo::create([
            'video_id' => $input['video_id'],
            'dashboard_playlist_id' => $input['playlist_id'],
            'name' => $input['name'],
            'description' => $input['description'],
            'source' => $input['source'],
            'thumbnail' => $input['thumbnail'],
            'file' => $input['file'],
        ]);

        return response()->json([
            'response' => $response
        ], 200);
    }

    public function getByPlaylist(Request $request)
    {
        $playlistId = Route::current()->parameter('id');

        $userId = $request->user()->id;

        $playlist = DashboardPlaylist::where('id', '=', $playlistId)
            ->where('user_id', '=', $userId)
            ->get();

        if (!$playlist) {
            return response()->json([
                'response' => 'Playlist does not exist.'
            ], 200);
        }

        $videoList = DashboardVideo::where('dashboard_playlist_id', '=', $playlistId)
            ->orderBy('updated_at')
            ->get();

        if (count($videoList) === 0) {
            return response()->json([
                'response' => []
            ], 200);
        }

        return response()->json([
            'response' => $videoList
        ], 200);
    }

    public function removeFromPlaylist(Request $request)
    {
        $playlistId = Route::current()->parameter('id');
        $id = Route::current()->parameter('video_id');

        $existingRecord = DashboardVideo::where('id', '=', $id)
            ->where('dashboard_playlist_id', '=', $playlistId)
            ->get();

        if (count($existingRecord) === 0) {
            return response()->json([
                'error' => 'Video not found.'
            ], 200);
        }
        
        RecentVideo::where('dashboard_video_id', '=', $id )
            ->delete();

        DashboardVideo::where('id', '=', $id)
            ->where('dashboard_playlist_id', '=', $playlistId)
            ->delete();

        return response()->json([
            'response' => $existingRecord
        ], 200);
    }

    private function getFormattedVideoList(Request $request)
    {
        $videos = $this->getVideoList($request);

        $newVideoList = [];
        foreach ($videos as $video) {
            $newVideoList[$video['id']] = $video;
        }
        return $newVideoList;
    }

    private function getVideoList(Request $request, string $searchText = '')
    {
        $videoList = [];
        $index = 0;
        $pageNumber = 1;
        $isAllVideosFetched = false;

        while (!$isAllVideosFetched) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 2f465678757a430ab50ef092d765c332'
            ])->get('https://api.vimeo.com/users/20619244/videos?per_page=100&page=' . $pageNumber . '&query=' . $searchText . '&fields=name,description,resource_key,privacy.view,files.link,pictures.sizes.0.link')->json();

            if (isset($response['data'])) {
                foreach ($response['data'] as $video) {
                    if (
                        reset($video['files'])['link'] != null &&
                        $video['privacy']['view'] != 'unlisted'
                        && ($video['privacy']['view'] == 'disable' || $video['privacy']['view'] == 'anybody'
                            || $video['privacy']['view'] == 'anybody')
                    ) {
                        $videoList[$index] = [];
                        $videoList[$index]['id'] = $video['resource_key'];
                        $videoList[$index]['name'] = $video['name'];
                        $videoList[$index]['description'] = $video['description'];
                        $videoList[$index]['file'] = reset($video['files'])['link'];
                        $videoList[$index]['thumbnail'] = $video['pictures']['sizes'][0]['link'];
                        $videoList[$index]['source'] = 'Vimeo';
                        $index++;
                    }
                }
                $pageNumber++;
            } else {
                $isAllVideosFetched = true;
            }
        }

        return $videoList;
    }

    private function getYTVideoList(Request $request, string $searchText = '')
    {

        $videoList = [];
        $index = 0;

        $response = Http::withHeaders([])->get('https://www.googleapis.com/youtube/v3/search?q=' . $searchText . '&part=snippet&maxResults=10&type=video&key=AIzaSyBT8qn_T4pOhulFjTMqVz_MnZ_Ih3_AgCY')->json();

        if (isset($response['items'])) {
            foreach ($response['items'] as $video) {
                $videoList[$index] = [];
                $videoList[$index]['id'] = $video['id']['videoId'];
                $videoList[$index]['name'] = $video['snippet']['title'];
                $videoList[$index]['description'] = $video['snippet']['description'];
                $videoList[$index]['file'] = 'https://www.youtube.com/embed/' . $video['id']['videoId'];
                $videoList[$index]['thumbnail'] = $video['snippet']['thumbnails']['default']['url'];
                $videoList[$index]['source'] = 'Youtube';
                $index++;
            }
            return $videoList;
        } else {
            return [];
        }
    }

    private function isSubscribed(Request $request)
    {
        $userId = $request->user()->id;

        $subscriptionRecord = Subscription::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_revoked', '=', false)
            ->first();

        return $subscriptionRecord != null;
    }
}
