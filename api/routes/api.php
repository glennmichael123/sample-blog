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

Route::group(['middleware' => 'auth:api'], function()
{
    Route::post('posts',         'PostsController@store');
    Route::patch('posts/{post}', 'PostsController@update');
    Route::delete('posts/{post}','PostsController@destroy');

    Route::post('posts/{post}/comments',            'CommentsController@store');
    Route::patch('posts/{post}/comments/{comment}', 'CommentsController@update');
    Route::delete('posts/{post}/comments/{comment}','CommentsController@destroy');

});

Route::post('/login',    'Auth\LoginController@login');
Route::post('/logout',   'Auth\LoginController@logout');
Route::post('/register', 'Auth\RegisterController@create');

Route::get('posts/{post}', 'PostsController@show');
Route::get('posts',        'PostsController@index');
Route::get('posts/{post}/comments', 'CommentsController@index');
