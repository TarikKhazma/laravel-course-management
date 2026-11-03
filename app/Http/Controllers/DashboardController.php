<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\TrainingCourse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'coursesCount' => Course::count(),
            'studentsCount' => Student::count(),
            'trainingCoursesCount' => TrainingCourse::count(),
            'enrollmentsCount' => DB::table('enrollments')->count(),
            'latestTrainingCourses' => TrainingCourse::with('course')
                ->withCount('students')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
