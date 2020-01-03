<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('task', 'TaskController@task')->name('createTask');
Route::post('task/{id}', 'TaskController@task')->name('updateTask');
Route::get('users', 'UserController@index')->name('getUsers');
