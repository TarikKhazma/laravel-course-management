<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainingCourseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('courses', CourseController::class)->except('show');
Route::resource('students', StudentController::class)->except('show');
Route::resource('training-courses', TrainingCourseController::class);

Route::controller(StudentController::class)->scopeBindings()->group(function () {
    Route::get('students/{student}/documents/{document}', 'downloadDocument')->name('students.documents.download');
    Route::delete('students/{student}/documents/{document}', 'destroyDocument')->name('students.documents.destroy');
});

Route::get('enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
Route::post('enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
Route::delete('training-courses/{training_course}/students/{student}', [EnrollmentController::class, 'destroy'])
    ->name('enrollments.destroy');
