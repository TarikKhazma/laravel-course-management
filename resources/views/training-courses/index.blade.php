@extends('layouts.app')

@section('title', 'الدورات التدريبية')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">قائمة الدورات التدريبية</h3>
            <div class="card-tools">
                <a href="{{ route('training-courses.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> إضافة دورة
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الدورة</th>
                        <th>الكورس</th>
                        <th>البداية</th>
                        <th>النهاية</th>
                        <th>المقاعد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trainingCourses as $trainingCourse)
                        <tr>
                            <td>{{ $trainingCourse->name }}</td>
                            <td>{{ $trainingCourse->course->name }}</td>
                            <td>{{ $trainingCourse->start_date->format('Y-m-d') }}</td>
                            <td>{{ $trainingCourse->end_date->format('Y-m-d') }}</td>
                            <td>{{ $trainingCourse->students_count }} / {{ $trainingCourse->seats }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('training-courses.show', $trainingCourse) }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-users"></i> الطلاب
                                    </a>
                                    <a href="{{ route('training-courses.edit', $trainingCourse) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    @include('partials.delete-button', [
                                        'action' => route('training-courses.destroy', $trainingCourse),
                                        'confirm' => 'هل تريد حذف هذه الدورة مع جميع تسجيلاتها؟',
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">لا توجد دورات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($trainingCourses->hasPages())
            <div class="card-footer">{{ $trainingCourses->links() }}</div>
        @endif
    </div>
@endsection
