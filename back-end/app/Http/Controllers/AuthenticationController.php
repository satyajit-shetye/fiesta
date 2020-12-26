<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    function signup (){
        return 'SignUp';
    }

    function signin (){
        return 'SignIn';
    }
}
