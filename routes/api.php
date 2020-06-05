<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* 用户登录 */
Route::post('/login', 'LoginController@login');
/* 登录组 */
Route::middleware('auth:api')->group(function (){
    /* 获取用户信息 */
    Route::get('/user', 'UserController@user');
    /* 修改用户信息 */
    Route::post('/user/update', 'UserController@update');
});
