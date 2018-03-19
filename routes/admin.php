<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/3/6
 * Time: 下午2:36
 */

Route::prefix('admin')->namespace('Admin')->group(function (){
    //管理员登录
    Route::get('/login',function (){return view('admin.login');});
    //验证码
    Route::get('/login/validate', function () {return captcha_src();});

    Route::post('/login', 'AdminController@login');
    Route::get('/logout', 'AdminController@logout');


    Route::group(['middleware' => ['admin.login']], function (){
        //页面渲染
        Route::get('/', function (){return view('admin.index');});



        Route::prefix('user')->group(function (){
            Route::get('/type', 'UserController@type');
            Route::get('/data', 'UserController@data');

            Route::post('/add', 'UserController@add');
            Route::post('/edit', 'UserController@edit');
            Route::post('/delete', 'UserController@delete');

            Route::post('/course/get', 'CourseController@courses');
            Route::post('/course/clear', 'CourseController@clearCourse');
            Route::post('/course/add', 'CourseController@addCourse');
            Route::post('/course/edit', 'CourseController@editCourse');
            Route::post('/course/delete', 'CourseController@deleteCourse');
        });

    });


});
Route::get('/admin/nodes','SettingController@nodes');