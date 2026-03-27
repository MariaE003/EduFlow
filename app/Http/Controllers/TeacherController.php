<?php

namespace App\Http\Controllers;

use App\Services\TeacherService;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller {
    protected $service;
    protected $groupService;

    public function __construct(TeacherService $service,GroupService $groupService){
        $this->service = $service;
        $this->groupService = $groupService;
    }

    public function students($courseId){
        $user = Auth::user();

        if ($user->role !== 'teacher'){
            return response()->json(['error' => 'non autorise'], 403);
        }
        $course = $this->service->getStudents($user->id, $courseId);
        if (!$course) {
            return response()->json(['error' => 'cours introuvable'], 404);
        }
        return response()->json($course);
    }

    public function stats(){
        $user = Auth::user();
        if ($user->role !== 'teacher') {
            return response()->json(['error' => 'non autorise'], 403);
        }
        $stats = $this->service->getStats($user->id);
        return response()->json($stats);
    }

    public function groups($courseId){
        $user = Auth::user();
        if($user->role!=='teacher'){
            return response()->json(['error'=>'non autorise'],403);
        }
        $groups = $this->groupService->getGroups($courseId);
        return response()->json($groups);
    }
}
?>