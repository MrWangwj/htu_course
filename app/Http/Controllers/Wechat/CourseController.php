<?php

namespace App\Http\Controllers\Wechat;

use App\Model\Setting;
use App\Model\Teacher;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    //
    public function count(){
        $term = Setting::getNowTerm();
        $collection = $user = User::with(['courses' => function($query) use($term){
            $query->where('term_id', $term->id);
        }])->orderBy('name_py')->get();

        $users = [];
        $grades = [];
        foreach ($collection->toArray() as $value){
            $tmpGrade = intval(substr($value['account'], 0,2));
            if($tmpGrade != 0 && !in_array($tmpGrade,$grades)) $grades[] = $tmpGrade;
            $users['u'.$value['id']] = $value;
        }

        $nowWeek = Setting::getNowWeek();
        return compact(['users', 'nowWeek', 'grades', 'term']);
    }

}
