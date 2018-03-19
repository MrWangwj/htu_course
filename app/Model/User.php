<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //不可被批量复制的字段
    public $guarded = ['id'];

    //关联课表
    public function courses(){
        return $this->belongsToMany(Course::class, 'user_course', 'user_id', 'course_id');
    }

    //关联专业
    public function majors(){
        return $this->belongsTo(Majors::class, 'major_id', 'id');
    }

    //获取用户所有类型
    public static function getType(){
        $majors = Majors::orderBy('id')->get(['id', 'name']);
        return compact('majors');
    }


}
