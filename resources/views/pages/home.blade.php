@extends('layouts.master')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('title', 'My Courses')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    <div class="container-fluid my-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <!-- Home Page Header -->
                <div class="card">
                    <div class="card-header cs-red text-white">
                        <h3 id="greeting">{{ $userName }}</h3>
                        <p class="mb-0">Here are the courses you're enrolled in:</p>
                        <p class="mb-0"><small id="currentDate"></small></p>
                    </div>


                    <!-- Courses Section -->
                    <div class="card-body mt-3">
                        <h4 class="text-danger">Your Courses</h4>

                        @if ($courses->isEmpty())
                            <div class="alert alert-warning mt-3" role="alert">
                                You are not currently enrolled in any courses. Please reach out to the Teaching Team for
                                assistance in enrolling.
                            </div>
                        @else
                            <ul class="list-group">
                                @foreach ($courses as $course)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Course Code:</strong> {{ $course->course_code }} <br>
                                            <strong>Course Name:</strong> {{ $course->name }}
                                        </div>
                                        <a href="{{ route('course-details', ['courseCode' => $course->course_code]) }}"
                                            class="btn btn-outline-danger btn-sm">Go to Course</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('footer')
    @include('layouts.footer')
@endsection
