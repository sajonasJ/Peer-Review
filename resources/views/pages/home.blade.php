@extends('layouts.master')

@section('nav-top')
@include('components.nav-top')
@endsection

@section('title', 'My Courses')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    <div class="container m-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Home Page Header -->
                <div class="card cs-red">
                    <div class="card-header text-white">
                        <h3 id="greeting"></h3>
                        <p class="mb-0">Here are the courses you're enrolled in:</p>
                        <p class="mb-0"><small id="currentDate"></small></p>
                    </div>
                </div>

                <!-- Courses Section -->
                <div class="mt-5">
                    <h4 class="text-danger">Your Courses</h4>

                    <ul class="list-group">
                        <!-- Static Course Examples -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Course Code:</strong> CS101 <br>
                                <strong>Course Name:</strong> Introduction to Computer Science
                            </div>
                            <!-- Placeholder for course details -->
                            <a href="#" class="btn btn-outline-danger btn-sm disabled">Go to Course</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Course Code:</strong> MTH102 <br>
                                <strong>Course Name:</strong> Calculus I
                            </div>
                            <!-- Placeholder for course details -->
                            <a href="#" class="btn btn-outline-danger btn-sm disabled">Go to Course</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Course Code:</strong> PHY103 <br>
                                <strong>Course Name:</strong> General Physics I
                            </div>
                            <!-- Placeholder for course details -->
                            <a href="#" class="btn btn-outline-danger btn-sm disabled">Go to Course</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Course Code:</strong> ENG104 <br>
                                <strong>Course Name:</strong> English Composition
                            </div>
                            <!-- Placeholder for course details -->
                            <a href="#" class="btn btn-outline-danger btn-sm disabled">Go to Course</a>
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
