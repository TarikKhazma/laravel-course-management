<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TrainingCourse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function create(): View
    {
        return view('enrollments.create', [
            'students' => Student::orderBy('full_name')->get(['id', 'full_name', 'email']),
            'trainingCourses' => TrainingCourse::withCount('students')->orderBy('start_date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'training_course_id' => ['required', 'exists:training_courses,id'],
        ]);

        // The row lock makes concurrent enrollments wait, so seats cannot be oversold.
        $error = DB::transaction(function () use ($data) {
            $trainingCourse = TrainingCourse::lockForUpdate()->findOrFail($data['training_course_id']);

            if ($trainingCourse->students()->whereKey($data['student_id'])->exists()) {
                return 'هذا الطالب مسجل بالفعل في هذه الدورة.';
            }

            if ($trainingCourse->students()->count() >= $trainingCourse->seats) {
                return 'لا توجد مقاعد متاحة في هذه الدورة.';
            }

            $trainingCourse->students()->attach($data['student_id']);

            return null;
        });

        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        return redirect()
            ->route('training-courses.show', $data['training_course_id'])
            ->with('success', 'تم تسجيل الطالب في الدورة بنجاح.');
    }

    public function destroy(TrainingCourse $trainingCourse, Student $student): RedirectResponse
    {
        $trainingCourse->students()->detach($student->id);

        return back()->with('success', 'تم إلغاء تسجيل الطالب من الدورة بنجاح.');
    }
}
