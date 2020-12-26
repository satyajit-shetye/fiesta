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
            'birth_date' => $input['birth_date'],
            'gender' => $input['gender'],
            'password' => Hash::make($input['password'])
        ]);

        return response()->json([
            'response' => $user
        ]);
    }

    function signin (){
        return 'SignIn';
    }
}
