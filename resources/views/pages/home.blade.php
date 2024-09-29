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
                        @foreach ($courses as $course)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Course Code:</strong> {{ $course->course_code }} <br>
                                    <strong>Course Name:</strong> {{ $course->name }}
                                </div>
                                <!-- Placeholder for course details -->
                                <a href="{{ route('course-details', ['id' => $course->id]) }}"
                                    class="btn btn-outline-danger btn-sm">Go to Course</a>
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
