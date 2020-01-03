<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = file_get_contents(config('global.usersEndpoint'));
        //dd();

        return response()->json([json_decode($users)], 201);
    }
}
