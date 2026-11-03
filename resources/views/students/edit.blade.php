@extends('layouts.app')

@section('title', 'تعديل بيانات الطالب')

@section('content')
    <div class="card">
        <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('students._form')
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">الملفات الحالية</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <tbody>
                    @forelse ($student->documents as $document)
                        <tr>
                            <td>{{ $document->original_name }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('students.documents.download', [$student, $document]) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-download"></i> تحميل
                                    </a>
                                    @include('partials.delete-button', [
                                        'action' => route('students.documents.destroy', [$student, $document]),
                                        'confirm' => 'هل تريد حذف هذا الملف؟',
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted">لا توجد ملفات مرفقة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
