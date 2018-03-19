<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //返回当前周
    public static function getNowWeek(){
        $startSchool = strtotime(self::where('key', 'startSchool')->first()->value);
        $nowTime = time();
        $week = ($nowTime - $startSchool) / (86400*7);
        return ceil($week) < 1 ? 1 : ceil($week);
    }

    //获取当前学期id和
    public static function getNowTerm(){
        $value = self::where('key', 'term')->first()->value;
        $term = Terms::select(['id', 'name', 'value'])->where('id', $value)->first();
        return $term;
    }
}
