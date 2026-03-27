<?php

namespace App\Repositories;

use App\Models\Course;

class TeacherRepository {
    public function getStudentsByCourse($teacherId, $courseId){
        return Course::where('teacher_id', $teacherId)
            ->where('id', $courseId)
            ->with(['enrollments.student'])
            ->first();
    }
    public function getCoursesStats($teacherId){
        return Course::where('teacher_id', $teacherId)->withCount([
                'enrollments as total_students','enrollments as active_students' => function ($q) {
                    $q->where('status', 'active');
                },
                'enrollments as cancelled_students' => function ($q) {
                    $q->where('status', 'cancelled');
                }
        ])->get();
    }
}
?>