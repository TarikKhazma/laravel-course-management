@extends('layouts.app')

@section('title', 'تسجيل طالب في دورة')

@section('content')
    <div class="card">
        <form action="{{ route('enrollments.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="student_id">الطالب</label>
                    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                        <option value="">اختر الطالب</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected((string) old('student_id') === (string) $student->id)>
                                {{ $student->full_name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="training_course_id">الدورة التدريبية</label>
                    <select id="training_course_id" name="training_course_id" class="form-control @error('training_course_id') is-invalid @enderror" required>
                        <option value="">اختر الدورة</option>
                        @foreach ($trainingCourses as $trainingCourse)
                            <option value="{{ $trainingCourse->id }}"
                                    @selected((string) old('training_course_id') === (string) $trainingCourse->id)
                                    @disabled($trainingCourse->availableSeats() === 0)>
                                {{ $trainingCourse->name }} — {{ $trainingCourse->availableSeats() === 0 ? 'مكتملة' : 'متاح '.$trainingCourse->availableSeats().' مقعد' }}
                            </option>
                        @endforeach
                    </select>
                    @error('training_course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">تسجيل</button>
            </div>
        </form>
    </div>
@endsection
