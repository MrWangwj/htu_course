<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    //关联用户表
    public function teachers(){
        return $this->hasMany(User::class, 'user_id', 'id');
    }
}
