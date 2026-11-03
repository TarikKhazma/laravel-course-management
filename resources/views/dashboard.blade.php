@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="row">
        @foreach ([
            ['الكورسات', $coursesCount, 'bg-info', 'fa-book', route('courses.index')],
            ['الطلاب', $studentsCount, 'bg-success', 'fa-user-graduate', route('students.index')],
            ['الدورات التدريبية', $trainingCoursesCount, 'bg-warning', 'fa-chalkboard-teacher', route('training-courses.index')],
            ['التسجيلات', $enrollmentsCount, 'bg-danger', 'fa-user-plus', route('enrollments.create')],
        ] as [$label, $count, $color, $icon, $link])
            <div class="col-lg-3 col-6">
                <div class="small-box {{ $color }}">
                    <div class="inner">
                        <h3>{{ $count }}</h3>
                        <p>{{ $label }}</p>
                    </div>
                    <div class="icon"><i class="fas {{ $icon }}"></i></div>
                    <a href="{{ $link }}" class="small-box-footer">عرض التفاصيل</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">أحدث الدورات التدريبية</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الدورة</th>
                        <th>الكورس</th>
                        <th>تاريخ البداية</th>
                        <th>المقاعد المتاحة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestTrainingCourses as $trainingCourse)
                        <tr>
                            <td><a href="{{ route('training-courses.show', $trainingCourse) }}">{{ $trainingCourse->name }}</a></td>
                            <td>{{ $trainingCourse->course->name }}</td>
                            <td>{{ $trainingCourse->start_date->format('Y-m-d') }}</td>
                            <td>{{ $trainingCourse->availableSeats() }} من {{ $trainingCourse->seats }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">لا توجد دورات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
