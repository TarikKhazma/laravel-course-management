@extends('layouts.app')

@section('title', 'الكورسات')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">قائمة الكورسات</h3>
            <div class="card-tools">
                <a href="{{ route('courses.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> إضافة كورس
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>عدد الساعات</th>
                        <th>الدورات</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ Str::limit($course->description, 60) }}</td>
                            <td>{{ $course->hours }}</td>
                            <td>{{ $course->training_courses_count }}</td>
                            <td>
                                <span class="badge badge-{{ $course->is_active ? 'success' : 'secondary' }}">
                                    {{ $course->is_active ? 'فعال' : 'غير فعال' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    @include('partials.delete-button', [
                                        'action' => route('courses.destroy', $course),
                                        'confirm' => 'هل تريد حذف هذا الكورس؟',
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">لا توجد كورسات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($courses->hasPages())
            <div class="card-footer">{{ $courses->links() }}</div>
        @endif
    </div>
@endsection
