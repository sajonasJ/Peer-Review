@extends('layouts.master')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('title', 'Course Details')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    <div class="container m-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Course Header -->
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h3>Course Title: {{ $course->name }}</h3>
                    </div>
                    <div class="card-body">
                        <h5><strong>Instructors:</strong></h5>
                        <ul>
                            @foreach ($course->teachers as $teacher)
                                <li>{{ $teacher->name }}</li>
                            @endforeach
                        </ul>

                        <!-- Buttons: Go Back to Home & Enroll a Student -->
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-danger">Go Back to Home</a>
                            <a href="#" class="btn btn-danger disabled">Enroll a Student</a>
                        </div>
                    </div>
                </div>

                <!-- Assessments Section -->
                <div class="mt-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger">Peer Review Assessments</h4>
                        <!-- Add Assessment Button -->
                        <a href="#" class="btn btn-danger disabled">Add Assessment</a>
                    </div>

                    <ul class="list-group mt-3">
                        @foreach ($course->assessments as $assessment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $assessment->title }}</span>
                                <span class="badge bg-danger text-white">Due: {{ $assessment->due_date }}</span>
                                <a href="#" class="btn btn-outline-danger btn-sm disabled">View Details</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
