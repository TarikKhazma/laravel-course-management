<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::withCount('trainingCourses')->latest()->paginate(10);

        return view('courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('courses.create', ['course' => new Course(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Course::create($this->validated($request));

        return redirect()->route('courses.index')->with('success', 'تم إضافة الكورس بنجاح.');
    }

    public function edit(Course $course): View
    {
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($this->validated($request));

        return redirect()->route('courses.index')->with('success', 'تم تعديل الكورس بنجاح.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->trainingCourses()->exists()) {
            return back()->with('error', 'لا يمكن حذف هذا الكورس لأنه مرتبط بدورات تدريبية. احذف دوراته أولاً.');
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'تم حذف الكورس بنجاح.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'hours' => ['required', 'integer', 'min:1', 'max:10000'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
