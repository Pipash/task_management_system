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

Route::group([
    'middleware' => ['cors'],
], function () {
    Route::post('task', 'TaskController@createTask')->name('createTask');
    Route::post('task/{id}', 'TaskController@updateTask')->name('updateTask');
    Route::get('tasks', 'TaskController@index')->name('index');
});
