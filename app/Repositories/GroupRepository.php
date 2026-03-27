<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\GroupMember;

class GroupRepository{
    public function getLastGroup($courseId){
        return Group::where('course_id', $courseId)
            ->orderBy('group_number', 'desc')
            ->first();
    }
    public function createGroup($courseId, $groupNumber){
        return Group::create([
            'course_id' => $courseId,
            'group_number' => $groupNumber
        ]);
    }

    public function countMembers($groupId){
        return GroupMember::where('group_id', $groupId)->count();
    }

    public function addStudentToGroup($groupId, $studentId){
        return GroupMember::create([
            'group_id' => $groupId,
            'student_id' => $studentId
        ]);
    }
    public function getGroupsByCourse($courseId){
        return Group::where('course_id', $courseId)->with('members.student')->get();
    }
}
?>