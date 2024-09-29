@extends('layouts.master')

@section('title', 'Assessment Details')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('nav')
    @include('layouts.nav')
@endsection

@section('content')
    <div class="container m-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Back Button on Top Left -->
                <div class="d-flex justify-content-start mb-3">
                    <button onclick="history.back()" class="btn btn-outline-danger">Back</button>
                </div>

                <!-- Assessment Details Card -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3>Assessment Details for {{ $assessment->title }}</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Course:</strong> {{ $course->course_code }} - {{ $course->name }}</p>
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
