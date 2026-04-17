<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CoursePaymentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\GroupController;

Route::apiResource('interests',InterestController::class);
Route::middleware('auth:api')->group(function() {
Route::apiResource('courses',CourseController::class);
Route::get('/recommended-courses', [CourseController::class, 'recommended'])->middleware('auth:api');
Route::apiResource('wishlist',WishlistController::class)->middleware('auth:api');
// Route::middleware('auth:api')->group(function(){
    Route::post('/courses/{courseId}/pay', [CoursePaymentController::class, 'payCourse']);
    Route::post('/courses/{courseId}/confirm', [CoursePaymentController::class, 'confirmPayment']);
// });

Route::delete('/courses/{id}/cancel', [CoursePaymentController::class, 'cancelEnrollment'])->middleware('auth:api');


// Route::middleware('auth:api')->group(function () {
    Route::get('/teacher/courses/{id}/students', [TeacherController::class, 'students']);
    Route::get('/teacher/stats', [TeacherController::class, 'stats']);
    Route::get('/courses/{id}/groups', [TeacherController::class, 'groups']);//a modifier

// });


Route::post('/logout', [AuthController::class,'logout']);
});

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);



