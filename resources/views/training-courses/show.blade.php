@extends('layouts.app')

@section('title', $trainingCourse->name)

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">بيانات الدورة</h3>
            <div class="card-tools">
                <a href="{{ route('training-courses.edit', $trainingCourse) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-edit"></i> تعديل
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>الكورس:</strong> {{ $trainingCourse->course->name }}</div>
                <div class="col-md-3"><strong>البداية:</strong> {{ $trainingCourse->start_date->format('Y-m-d') }}</div>
                <div class="col-md-3"><strong>النهاية:</strong> {{ $trainingCourse->end_date->format('Y-m-d') }}</div>
                <div class="col-md-3"><strong>المقاعد المتاحة:</strong> {{ $trainingCourse->availableSeats() }} من {{ $trainingCourse->seats }}</div>
            </div>
            @if ($trainingCourse->description)
                <p class="mt-3 mb-0">{{ $trainingCourse->description }}</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">تسجيل طالب في هذه الدورة</h3>
        </div>
        <div class="card-body">
            @if ($trainingCourse->availableSeats() === 0)
                <p class="text-danger mb-0">اكتملت مقاعد هذه الدورة.</p>
            @elseif ($availableStudents->isEmpty())
                <p class="text-muted mb-0">لا يوجد طلاب متاحون للتسجيل.</p>
            @else
                <form action="{{ route('enrollments.store') }}" method="POST" class="form-row align-items-end">
                    @csrf
                    <input type="hidden" name="training_course_id" value="{{ $trainingCourse->id }}">
                    <div class="form-group col-md-8 mb-md-0">
                        <label for="student_id">الطالب</label>
                        <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                            <option value="">اختر الطالب</option>
                            @foreach ($availableStudents as $student)
                                <option value="{{ $student->id }}" @selected((string) old('student_id') === (string) $student->id)>
                                    {{ $student->full_name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-block">تسجيل</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">الطلاب المسجلون ({{ $trainingCourse->students_count }})</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->pivot->created_at?->format('Y-m-d') }}</td>
                            <td>
                                @include('partials.delete-button', [
                                    'action' => route('enrollments.destroy', [$trainingCourse, $student]),
                                    'confirm' => 'هل تريد إلغاء تسجيل هذا الطالب من الدورة؟',
                                    'label' => 'إلغاء التسجيل',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">لا يوجد طلاب مسجلون بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            <div class="card-footer">{{ $students->links() }}</div>
        @endif
    </div>
@endsection
