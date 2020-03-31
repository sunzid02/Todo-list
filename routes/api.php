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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('tasks', 'TaskController@index');
Route::post('task', 'TaskController@store');
Route::put('task', 'TaskController@update');
Route::delete('task/{id}', 'TaskController@destroy');

Route::put('complete-task/{id}', 'TaskController@completeTask');
Route::delete('clear-complete-task', 'TaskController@completeTaskDestroy');
// Route::get('complete-tasks', 'TaskController@completeTaskList');

