<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function loggedInUser(){
        return 'Logged In User';
    }

    function updateUserDetails(){
        return 'Update User Details';
    }
}
