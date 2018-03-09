<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Jobtype extends Model
{
    //
    //关联教师表
    public function teachers(){
        return $this->hasMany(Teacher::class, 'teacher_id', 'id');
    }
}
