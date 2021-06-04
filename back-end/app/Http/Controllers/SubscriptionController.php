<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    function subscribe(Request $request){

        $userId = $request->user()->id;
 
        $subscriptionRecord = Subscription::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->where('is_revoked', '=', false)
        ->first();

        if($subscriptionRecord == null){
            $subscription = Subscription::create([
                'start_date' => date('Y-m-d H:i:s', strtotime(now())),
                'end_date' =>  date('Y-m-d H:i:s', strtotime('+1 year', strtotime(now()))),
                'user_id' => $userId
            ]);

            return response()->json([
                'response' => $subscription
            ]);
        } else {
            return response()->json([
                'errors' => 'User is already subscribed. The subscription will end on ' . date('m/d/Y', strtotime($subscriptionRecord['end_date']))
            ]);
        }
    }

    function isSubscribed(Request $request){
        $userId = $request->user()->id;

        $subscriptionRecord = Subscription::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->where('is_revoked', '=', false)
        ->first();

        return response()->json([
            'response' => $subscriptionRecord != null
        ]);

    }

    function revoke(Request $request){
        $userId = $request->user()->id;

        $subscriptionRecord = Subscription::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->where('is_revoked', '=', false)
        ->first();

        if($subscriptionRecord != null){
            $subscription = Subscription::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_revoked', '=', false)
            ->update(['is_revoked' => true]);

            return response()->json([
                'response' => $subscriptionRecord
            ]);
        } else {
            return response()->json([
                'errors' => 'User is not subscribed.'
            ]);
        }

    }
}
