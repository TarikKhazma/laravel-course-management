@extends('layouts.app')

@section('title', 'الطلاب')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">قائمة الطلاب</h3>
            <div class="card-tools">
                <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> إضافة طالب
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>الاسم الكامل</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>الملفات</th>
                        <th>الدورات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>
                                @if ($student->photo)
                                    <img src="{{ $student->photoUrl() }}" alt="{{ $student->full_name }}" class="student-photo">
                                @else
                                    <span class="student-photo-placeholder"><i class="fas fa-user"></i></span>
                                @endif
                            </td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->email }}</td>
                            <td dir="ltr" class="text-right">{{ $student->phone }}</td>
                            <td>{{ $student->documents_count }}</td>
                            <td>{{ $student->training_courses_count }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    @include('partials.delete-button', [
                                        'action' => route('students.destroy', $student),
                                        'confirm' => 'هل تريد حذف هذا الطالب مع صورته وملفاته وتسجيلاته؟',
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">لا يوجد طلاب بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            <div class="card-footer">{{ $students->links() }}</div>
        @endif
    </div>
@endsection
