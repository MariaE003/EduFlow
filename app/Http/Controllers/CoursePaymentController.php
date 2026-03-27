<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\GroupService;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class CoursePaymentController extends Controller
{
    protected $paymentService;
    protected $groupService;

    public function __construct(PaymentService $paymentService , GroupService $groupService){
        $this->paymentService = $paymentService;
        $this->groupService = $groupService;
    }
    public function payCourse(Request $request, $courseId){
        $user = Auth::user();

        if ($user->role !== 'student') {
            return response()->json(['error' => 'non autorise'], 403);
        }
        // search cours
        $course = Course::findOrFail($courseId);

        $paymentIntent = $this->paymentService->createPayment($course->price);
        // retourner client_secret au frontend
        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'course' => $course
        ]);
    }

    public function confirmPayment(Request $request, $courseId){
        $user = Auth::user();
        if ($user->role !== 'student'){
            return response()->json(['error' => 'non autorise'], 403);
        }
        $course = Course::findOrFail($courseId);

        Enrollment::create([
            'student_id' => $user->id,
            'course_id' => $courseId,
            'status' => 'active',
            'payment_status' => 'paid',
            'payment_id' => $request->payment_id, 
            'amount' => $course->price,
        ]);
        $this->groupService->assignStudent($courseId,$user->id);
        return response()->json([
            'message' => 'done',
            'course' => $course
        ]);
    }

    public function cancelEnrollment($courseId){
        $user = auth()->user();
        if ($user->role !== 'student') {
            return response()->json(['error' => 'non autorise'], 403);
        }
        $enrollment = Enrollment::where('student_id', $user->id)
            ->where('course_id', $courseId)
            ->first();
        // dd($enrollment);
        if (!$enrollment) {
            return response()->json(['error' => 'inscription non trouve'], 404);
        }
        $enrollment->update([
            'status' => 'cancelled'
        ]);
        return response()->json([
            'message' => 'desinscription reussie'
        ]);
        }
}