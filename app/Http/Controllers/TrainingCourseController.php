<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\TrainingCourse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TrainingCourseController extends Controller
{
    public function index(): View
    {
        $trainingCourses = TrainingCourse::with('course')->withCount('students')->latest()->paginate(10);

        return view('training-courses.index', compact('trainingCourses'));
    }

    public function create(): View
    {
        return view('training-courses.create', [
            'trainingCourse' => new TrainingCourse,
            'courses' => $this->selectableCourses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        TrainingCourse::create($this->validated($request));

        return redirect()->route('training-courses.index')->with('success', 'تم إضافة الدورة التدريبية بنجاح.');
    }

    public function show(TrainingCourse $trainingCourse): View
    {
        $trainingCourse->load('course')->loadCount('students');
        $students = $trainingCourse->students()->orderBy('full_name')->paginate(15);
        $availableStudents = Student::whereDoesntHave(
            'trainingCourses',
            fn ($query) => $query->whereKey($trainingCourse->id)
        )->orderBy('full_name')->get(['id', 'full_name', 'email']);

        return view('training-courses.show', compact('trainingCourse', 'students', 'availableStudents'));
    }

    public function edit(TrainingCourse $trainingCourse): View
    {
        return view('training-courses.edit', [
            'trainingCourse' => $trainingCourse,
            'courses' => $this->selectableCourses($trainingCourse->course_id),
        ]);
    }

    public function update(Request $request, TrainingCourse $trainingCourse): RedirectResponse
    {
        $trainingCourse->update($this->validated($request, $trainingCourse));

        return redirect()->route('training-courses.index')->with('success', 'تم تعديل الدورة التدريبية بنجاح.');
    }

    public function destroy(TrainingCourse $trainingCourse): RedirectResponse
    {
        $trainingCourse->delete();

        return redirect()->route('training-courses.index')->with('success', 'تم حذف الدورة التدريبية بنجاح.');
    }

    /** Active courses, plus the training's current course so editing never loses it. */
    private function selectableCourses(?int $currentCourseId = null): Collection
    {
        return Course::where('is_active', true)
            ->when($currentCourseId, fn ($query) => $query->orWhere('id', $currentCourseId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function validated(Request $request, ?TrainingCourse $trainingCourse = null): array
    {
        $enrolled = $trainingCourse?->students()->count() ?? 0;

        return $request->validate([
            'course_id' => [
                'required',
                Rule::exists('courses', 'id')->whereNull('deleted_at')->where(
                    fn ($query) => $query->where('is_active', true)
                        ->when($trainingCourse, fn ($q) => $q->orWhere('id', $trainingCourse->course_id))
                ),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'seats' => ['required', 'integer', 'min:'.max(1, $enrolled), 'max:10000'],
        ]);
    }
}
