<?php

namespace App\Http\Controllers\Admin;

use App\Model\Course;
use App\Model\Setting;
use App\Model\Teacher;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function Symfony\Component\Debug\Tests\testHeader;

class CourseController extends Controller
{
    //用户课表
    public function courses(){
        $term = Setting::getNowTerm();
        $user = User::with(['courses' => function($query) use($term){
            $query->where('term_id', $term->id);
        }])->where('id', \request('id'))->first();
        if($user)
            return $this->returnJSON(1, 'success', $user);
        return $this->returnJSON(0, '人员不存在');
    }

    //清除所有课表
    public function clearCourse(){
//        $this->validate(request(),[
//            'user-id' => 'required|numeric|exists:users,id',
//        ]);

        $user = User::where('id', request('id'))->first();
        $keys = [];
        foreach ($user->courses as $value){
            $keys[] = $value->id;
        }
        $user->courses()->detach($keys);
        return ['code' => 1, 'msg' => '清除成功'];
    }

    //添加课表
    public function addCourse(){
        //验证
//        $this->validate(\request(), [
//            'user-id' => 'required|numeric|exists:users,id',
//            'name' => 'required|max:150',
//            'teacher' => 'required|max:45',
//            'location' => 'required|max:150',
//            'start_week' => 'required|Integer|between:1,20',
//            'week_end' => 'required|Integer|between:1,20',
//            'section_start' => [
//                'required',
//                Rule::in([1, 3, 5, 6, 8, 10, 12]),
//            ],
//            'end_section' => [
//                'required',
//                Rule::in([2, 4, 5, 7, 9, 11, 12]),
//            ],
//            'week_day' => 'required|Integer|between:1,7',
//            'week_type' => [
//                'required',
//                Rule::in([0, 1, 2]),
//            ]
//        ]);
        $request = request();
        if($request['week_start'] > $request['week_end'] || $request['section_start'] > $request['section_end']){
            return ['code' => 0, 'msg' => '错误操作'];
        }

        $user = User::with('courses')->find($request['id']);

        $week_day_course = $user->courses->where('week_day', $request['week_day']);

        $result = $week_day_course->filter(function ($value, $key) use($request){
            if($value->week_type ==0 || $value->week_type = $request['week_type']){
                if(max($request['section_start'], $value->section_start)
                    <= min($request['section_end'], $value->section_end)){

                    if(max($request['week_start'], $value->week_start)
                        <= min($request['week_end'], $value->week_end)){
                        return true;
                    }

                }
            }
        });

        if($result->count() > 0){
            return ['code' => 0, 'msg' => '课程冲突'.$result->first()->name];
        }

        $data = request(['name', 'teacher', 'location', 'week_start', 'week_end', 'section_start', 'section_end', 'week_day', 'week_type']);
        $data['term_id'] = $term = Setting::getNowTerm()->id;
        //存储
        $course = (Course::firstOrCreate($data));
        if(!$course){
            return ['code' => 1, 'msg' => '添加失败'];
        }
        $user->courses()->attach($course->id);
        return ['code' => 1, 'msg' => '添加成功'];
        //返回
    }

    //更新课表
    public function editCourse(){
//        $this->validate(\request(), [
//            'user-id' => 'required|numeric|exists:users,id',
//            'id' => 'required|numeric|exists:courses,id',
//            'name' => 'required|max:150',
//            'teacher' => 'required|max:45',
//            'location' => 'required|max:150',
//            'week_start' => 'required|Integer|between:1,20',
//            'week_end' => 'required|Integer|between:1,20',
//            'section_start' => [
//                'required',
//                Rule::in([1, 3, 5, 6, 8, 0, 12]),
//            ],
//            'section_end' => [
//                'required',
//                Rule::in([2, 4, 5, 7, 9, 11, 12]),
//            ],
//            'week_day' => 'required|Integer|between:1,7',
//            'week_type' => [
//                'required',
//                Rule::in([0, 1, 2]),
//            ]
//        ]);
        $request = request();
        if($request['week_start'] > $request['week_end'] || $request['section_start'] > $request['section_end']){
            return ['code' => 0, 'msg' => '错误操作'];
        }

        $user = User::with('courses')->find($request['user_id']);

        $week_day_course = $user->courses->where('week_day', $request['week_day']);

        $result = $week_day_course->filter(function ($value, $key) use($request){
            if($value->id == $request['id'])
                return false;
            if($value->week_type ==0 || $value->week_type = $request['week_type']){
                if(max($request['section_start'], $value->section_start)
                    <= min($request['section_end'], $value->section_end)){

                    if(max($request['week_start'], $value->week_start)
                        <= min($request['week_end'], $value->week_end)){
                        return true;
                    }

                }
            }
        });

        if(!$result->isEmpty()){
            return ['code' => 0, 'msg' => '课程冲突,'.$result->first()->name];
        }

        $data = request(['name', 'teacher', 'location', 'week_start', 'week_end', 'section_start', 'section_end', 'week_day', 'week_type']);
        $data['term_id'] = $term = Setting::getNowTerm()->id;
        //存储
        $course = (Course::firstOrCreate($data));
        if(!$course){
            return ['code' => 0, 'msg' => ' 修改失败'];
        }

        if($course->id != $request['id']){
            $user->courses()->detach($request['id']);
            $user->courses()->attach($course->id);
        }
        return ['code' => 1, 'msg' => '修改成功'];
        //返回
    }

    //删除课表
    public function deleteCourse(){
//        $this->validate(request(),[
//            'user-id' => 'required|numeric|exists:users,id',
//            'id' => 'required|numeric|exists:courses,id',
//        ]);
        $user = User::where('id', request('user_id'))->first();
        $user->courses()->detach(request('id'));
        return ['code' => 1, 'msg' => '删除成功'];
    }
}
