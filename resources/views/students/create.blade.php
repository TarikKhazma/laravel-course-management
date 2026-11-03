@extends('layouts.app')

@section('title', 'إضافة طالب')

@section('content')
    <div class="card">
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('students._form')
        </form>
    </div>
@endsection
