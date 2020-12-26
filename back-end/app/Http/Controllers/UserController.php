<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    function loggedInUser(Request $request){
        return $request->user();
    }

    function updateUserDetails(Request $request){
        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'boolean'],
            'birthdate' => ['required', 'date'],
        ],$messages = [
            'boolean' => 'The :attribute field is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $user = User::find($request->user()['id']);

        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->birthdate = date('Y-m-d',strtotime($input['birthdate']));
        $user->gender = $input['gender'];
        $user->save();

        return response()->json([
            'response' => $user
        ]);
    }
}
