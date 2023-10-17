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

    Route::post('/admin/login','admin/login');
});


Route::group('/api', function () {
    Route::get('/user/getall', 'user/getAll');

    Route::get('/user/:id', 'user/getById');

    Route::get('/user/getpage','user/getPage');

    Route::post('/user/resetpwd','user/resetPwd');

    Route::delete('/user/delete/:id','user/deleteUserById');
    
    Route::post('/user/edit','user/editUser');

    Route::post('/admin/add','admin/addAdmin');

    Route::post('/admin/edit','admin/editAdmin');

    Route::delete('/admin/delete/:id','admin/deleteAdmin');

    Route::get('/admin/getall','admin/getAdmin');

    Route::get('/admin/getpage','admin/getPage');

    Route::get('/order/getpage','order/getPage');
    
    Route::post('/order/edit','order/editPage');

    Route::post('/order/add','order/addOrder');

    Route::delete('/order/delete/:id','order/deleteOrder');

    Route::get('/order/user/:u_id','order/getByUserId');
    
    Route::get('/recharge/getpage','recharge/getPage');

    Route::delete('/recharge/delete/:id','recharge/deleteRecord');

    Route::post('/recharge/add','recharge/addRecord');


})->middleware(JwtMiddleware::class);