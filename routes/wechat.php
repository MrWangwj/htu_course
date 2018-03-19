<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/3/6
 * Time: 下午2:35
 */

Route::prefix('/wechat')->namespace('Wechat')->group(function (){
    Route::get('/', function (){
        return view('wechat.index');
    });

    Route::get('/user/type', 'UserController@types');
    Route::get('/course/count', 'CourseController@count');
});