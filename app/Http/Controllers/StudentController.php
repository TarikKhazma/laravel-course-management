<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::withCount(['trainingCourses', 'documents'])->latest()->paginate(10);

        return view('students.index', compact('students'));
    }

    public function create(): View
    {
        return view('students.create', ['student' => new Student]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        $student = Student::create($data);
        $this->storeDocuments($request, $student);

        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح.');
    }

    public function edit(Student $student): View
    {
        $student->load('documents');

        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validated($request, $student);
        $oldPhoto = $student->photo;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        $student->update($data);
        $this->storeDocuments($request, $student);

        if ($oldPhoto && $oldPhoto !== $student->photo) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب بنجاح.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $photo = $student->photo;
        $documents = $student->documents()->pluck('path')->all();

        $student->delete();

        if ($photo) {
            Storage::disk('public')->delete($photo);
        }
        Storage::disk('local')->delete($documents);

        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح.');
    }

    public function downloadDocument(Student $student, StudentDocument $document): StreamedResponse
    {
        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    public function destroyDocument(Student $student, StudentDocument $document): RedirectResponse
    {
        $document->delete();
        Storage::disk('local')->delete($document->path);

        return back()->with('success', 'تم حذف الملف بنجاح.');
    }

    private function storeDocuments(Request $request, Student $student): void
    {
        foreach ($request->file('documents', []) as $file) {
            $student->documents()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $file->store('students/documents', 'local'),
            ]);
        }
    }

    private function validated(Request $request, ?Student $student = null): array
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student)],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'documents' => ['nullable', 'array', 'max:3'],
            'documents.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:2048'],
        ]);

        unset($data['photo'], $data['documents']);

        return $data;
    }
}
