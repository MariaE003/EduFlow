<?php
use Illuminate\Support\Facades\Route;

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::view('/', 'index')->name('home');

Route::get('/courses/{id}', function ($id) {
    return view('courses.show', ['id' => $id]);
})->name('courses.show');

Route::view('/wishlist', 'courses.wishlist')->name('wishlist');
Route::view('/my-courses', 'courses.myCourses')->name('my-courses');
Route::view('/recommendations', 'courses.recommendations')->name('recommendations');

Route::view('/dashboard', 'teacher.dashboard')->name('dashboard');
Route::view('/teacher/stats', 'teacher.stats')->name('teacher.stats');
Route::view('/teacher/courses', 'teacher.courses.index')->name('teacher.courses.index');
Route::view('/teacher/courses/create', 'teacher.courses.create')->name('teacher.courses.create');

Route::get('/teacher/courses/{id}/edit', function ($id) {
    return view('teacher.courses.edit', ['id' => $id]);
})->name('teacher.courses.edit');

Route::get('/teacher/courses/{id}/students', function ($id) {
    return view('teacher.students', ['id' => $id]);
})->name('teacher.students');

Route::get('/teacher/courses/{id}/groups', function ($id) {
    return view('groups.index', ['id' => $id]);
})->name('teacher.groups');