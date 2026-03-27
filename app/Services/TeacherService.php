<?php

namespace App\Services;

use App\Repositories\TeacherRepository;

class TeacherService {
    protected $repo;

    public function __construct(TeacherRepository $repo){
        $this->repo=$repo;
    }
    public function getStudents($teacherId, $courseId){
        return $this->repo->getStudentsByCourse($teacherId, $courseId);
    }
    public function getStats($teacherId){
        return $this->repo->getCoursesStats($teacherId);
    }
}

?>