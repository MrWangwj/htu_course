<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Overtrue\LaravelPinyin\Facades\Pinyin;

class UserController extends Controller
{

    //获取用户信息
    function data(){
        $user = User::with(['majors'])->orderBy('name_py')->get();
        return $user;
    }

    //获取用户类型
    function type(){
        $data = User::getType();
        return $data;
    }

    //添加用户
    function add(){
        $data = \request(['account', 'name', 'sex', 'major_id']);

        $data['name_py'] = implode(' ', Pinyin::convert(\request('name')));
        $user = User::create($data);
        if($user)
            return $this->returnJSON(1, '添加成功');
        return $this->returnJSON(0, '添加失败');
    }

    //编辑用户
    function edit(){

        $user = User::where('id', \request('id'))->first();
        $data = \request(['account','name', 'sex', 'major_id']);
        $data['name_py'] = implode(' ', Pinyin::convert(\request('name')));
        $user->fill($data);
        $result = $user->save();
        if($result)
            return $this->returnJSON(1, '修改成功');
        return $this->returnJSON(0, '修改失败');
    }

    //删除用户
    function delete(){
        $result = User::destroy(\request('id'));
        if($result)
            return $this->returnJSON(1, '删除成功');
        return $this->returnJSON(0, '删除失败');
    }
}
