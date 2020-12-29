<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    function signup (Request $request){
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
            'gender' => ['required', 'boolean'],
        ],$messages = [
            'boolean' => 'The :attribute field is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $user = User::create([
            'email' => $input['email'],
            'birthdate' => date('Y-m-d',strtotime($input['birthdate'])),
            'gender' => $input['gender'],
            'password' => Hash::make($input['password'])
        ]);

        return response()->json([
            'response' => $user
        ]);
    }

    public function signin(Request $request) {

        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => ['required'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 200);
        }
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => [
                    'email' => ['The provided credentials are incorrect.'],
                ]
            ], 200);
        }
    
        return response()->json([
            'response' => $user->createToken($user->email)->plainTextToken
        ], 200);
    }

    function logout(Request $request){
        $request->user()->currentAccessToken()->delete();;
        return response()->json([
            'response' => 'User logged out successfully.'
        ], 200);
    }
}
