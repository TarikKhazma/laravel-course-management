@extends('layouts.app')

@section('title', 'إضافة كورس')

@section('content')
    <div class="card">
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            @include('courses._form')
        </form>
    </div>
@endsection
