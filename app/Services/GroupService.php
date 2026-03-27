<?php

namespace App\Services;

use App\Repositories\GroupRepository;

class GroupService {
    protected $repo;

    public function __construct(GroupRepository $repo){
        $this->repo = $repo;
    }

    public function assignStudent($courseId, $studentId){
        $group = $this->repo->getLastGroup($courseId);
        if (!$group) {
            $group = $this->repo->createGroup($courseId, 1);
        }
        $count = $this->repo->countMembers($group->id);

        if ($count >= 25) {
            $group = $this->repo->createGroup($courseId, $group->group_number + 1);
        }

        return $this->repo->addStudentToGroup($group->id, $studentId);
    }

    public function getGroups($courseId){
        return $this->repo->getGroupsByCourse($courseId);
    }
}
?>