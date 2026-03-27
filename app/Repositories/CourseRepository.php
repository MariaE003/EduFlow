<?php 
namespace App\Repositories;

use App\Models\Course;

class CourseRepository{//acces db
    public function getAll(){
        $coures=Course::all();
        return $coures;
    }
    public function findById($id){
        return Course::find($id);
    }
    public function create($data){
        return Course::create($data);
    }
    public function update($id,$data){
        $course=Course::find($id);
        $course->update($data);
        return $course;
    }
    public function delete($id){
        $course=Course::find($id);
        return $course->delete();
    }
    public function getByInterests($interestIds){
        return Course::whereIn('interest_id',$interestIds)->get();
    }
}