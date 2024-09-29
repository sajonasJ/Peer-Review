@extends('layouts.master')

@section('title', 'Assessment Details')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="course-title px-3 py-2">
            <div class="d-flex gap-4 justify-content-start align-items-center">
                <a href="{{ route('course-details', ['courseCode' => $course->course_code]) }}"
                    class="btn btn-sm h-25 btn-warning">Back</a>
                <h3>Course: {{ $course->course_code }} - {{ $course->name }}</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card my-3">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <h4>Assessment Details</h4>
                        <a href="{{ route('edit-assessment', ['courseCode' => $course->course_code, 'assessmentId' => $assessment->id]) }}"
                            class="btn btn-sm btn-warning">Edit</a>
                    </div>
                    <div class="card-body">
                        <p><strong>Assessment Title:</strong> {{ $assessment->title }}</p>
                        <p><strong>Instructions:</strong> {{ $assessment->instruction }}</p>
                        <p><strong>Number of Reviews:</strong> {{ $assessment->num_reviews }}</p>
                        <p><strong>Maximum Score:</strong> {{ $assessment->max_score }}</p>
                        <p><strong>Due Date:</strong> {{ $assessment->due_date }}</p>
                        <p><strong>Due Time:</strong> {{ $assessment->due_time }}</p>
                        <p><strong>Review Type:</strong> {{ $assessment->type }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('footer')
    @include('layouts.footer')
@endsection
