<?php

namespace App\Http\Controllers\Wechat;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //获取用户类型
    function types(){
        $data = User::getType();
        return $data;
    }
}
