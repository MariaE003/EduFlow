<?php
namespace App\Services;
use App\Repositories\CourseRepository;

class CourseService{
    protected $courseRepo;
    public function __construct(CourseRepository $courseRepo){
        $this->courseRepo=$courseRepo;
    }
    public function allCourses(){
        return $this->courseRepo->getAll();
    }
    public function getCourseById($id){
        return $this->courseRepo->findById($id);
    }
    public function createCourse($data){
        return $this->courseRepo->create($data);
    }
    public function updateCourse($id,$data){
        return $this->courseRepo->update($id,$data);
    }
    public function deleteCourse($id){
        $this->courseRepo->delete($id);
    }
    public function getCoursesIntersted(){
        // dd(auth()->user());
        $user=auth()->user();
        $interestIds=$user->interests->pluck('id');
        // dd($interestIds);
        return $this->courseRepo->getByInterests($interestIds);
    }
}




?>