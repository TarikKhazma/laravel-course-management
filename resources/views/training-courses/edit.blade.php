@extends('layouts.app')

@section('title', 'تعديل الدورة التدريبية')

@section('content')
    <div class="card">
        <form action="{{ route('training-courses.update', $trainingCourse) }}" method="POST">
            @csrf
            @method('PUT')
            @include('training-courses._form')
        </form>
    </div>
@endsection
