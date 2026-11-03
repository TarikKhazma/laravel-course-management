@extends('layouts.app')

@section('title', 'تعديل الكورس')

@section('content')
    <div class="card">
        <form action="{{ route('courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')
            @include('courses._form')
        </form>
    </div>
@endsection
