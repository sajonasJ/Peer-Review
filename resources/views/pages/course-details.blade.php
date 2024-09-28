@extends('layouts.master')

@section('title', 'Course Details')

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
        <div class="row">
            <div class="col-md-12">
                <!-- Course Header -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3>Course Title: Introduction to Computer Science</h3>
                    </div>
                    <div class="card-body">
                        <h5><strong>Instructors:</strong></h5>
                        <ul>
                            <li>Dr. Jane Smith</li>
                            <li>Prof. John Doe</li>
                            <li>Ms. Alice Johnson</li>
                        </ul>

                        <!-- Buttons: Go Back to Home & Enroll a Student -->
                        <div class="d-flex justify-content-between mt-3">
                            <a href="/home" class="btn btn-outline-danger">Go Back to Home</a>
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
                        <!-- Static Examples of Assessments -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Week 1 Peer Review</span>
                            <span class="badge bg-danger text-white">Due: 2024-09-30</span>
                            <a href="#" class="btn btn-outline-danger btn-sm disabled">View Details</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Week 3 Peer Review</span>
                            <span class="badge bg-danger text-white">Due: 2024-10-14</span>
                            <a href="#" class="btn btn-outline-danger btn-sm disabled">View Details</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Week 5 Peer Review</span>
                            <span class="badge bg-danger text-white">Due: 2024-10-28</span>
                            <a href="#" class="btn btn-outline-danger btn-sm disabled">View Details</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
