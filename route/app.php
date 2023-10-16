<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
use app\middleware\JwtMiddleware;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');


Route::group('', function () {
    Route::post('/user/login', 'user/login');
    Route::post('/user/register', 'user/register');
});


Route::group('/api', function () {
    Route::get('/user/getall', 'user/getAll');
    Route::get('/user/:id', 'user/getById');
})->middleware(JwtMiddleware::class);