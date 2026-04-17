<?php
namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller{
    protected $courseService;
    public function __construct(CourseService $courseService){
        $this->courseService=$courseService;
    }

    public function index(){
        return $this->courseService->allCourses();
    }
    public function show($id){
        $course = $this->courseService->getCourseById($id);
        return response()->json($course);
    }

    public function store(Request $request){
        // dd($request->all());
        // return $this->courseService->createCourse($request->all());
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric'
        ]);
        $data = $request->all();
        $data['teacher_id'] = auth()->id();
        return $this->courseService->createCourse($data);
    }

    public function update($id,Request $request){
        return $this->courseService->updateCourse($id, $request->all());
    }

    public function destroy($id){
        return $this->courseService->deleteCourse($id);
    }

    public function recommended(){
        $courses=$this->courseService->getCoursesIntersted();
        return response()->json($courses);
    }
}


?>