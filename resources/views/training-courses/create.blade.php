@extends('layouts.app')

@section('title', 'إضافة دورة تدريبية')

@section('content')
    <div class="card">
        <form action="{{ route('training-courses.store') }}" method="POST">
            @csrf
            @include('training-courses._form')
        </form>
    </div>
@endsection
