<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $base = explode('/', asset('/'));
    $public = '';
    if ($base[2] == 'localhost') {
        $public = 'public/';
    }
    $data['public'] = $public;

    return view('index',$data);
});
