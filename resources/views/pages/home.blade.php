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
                <div class="card d-flex w-100 justify-content-center align-items-center">
                    <div class="card-header w-100 cs-red text-white d-flex justify-content-between align-items-stretch">
                        <div class="p-0">
                            <h3 id="greeting"></h3>
                            <h4 id="userName" class="mb-2">{{ $userName }}</h4>
                            <div class="text-start">
                                @if (Auth::guard('teacher')->check())
                                    <p id="userTypeText mb-0">Here are the courses you are teaching:</p>
                                @elseif (Auth::guard('web')->check())
                                    <p id="userTypeText mb-0">Here are the courses you're enrolled in:</p>
                                @else
                                    <p class="text-muted">You are not logged in.</p>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-content-between flex-column justify-content-between">
                            <p class="mb-0"><small id="currentDate"></small></p>
                            <div class="align-self-end">
                                @if (Auth::guard('teacher')->check())
                                    <button id="addCourseButton" class="btn btn-warning btn-sm btn-csw10">Add
                                        Course</button>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- File Upload Section -->
                    <!-- Add Course with Students and Teachers Form -->
                    <div id="addCourseCard" class="card col-md-8 mt-4 d-none">
                        <div class="card-header cs-red text-white">
                            <h5>Add New Course</h5>
                        </div>
                        <div class="card-body">
                            <form id="uploadForm" action="{{ route('import-course-data') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="courseFile" class="form-label">Upload Course File ( .json file )</label>
                                    <input type="file" name="courseFile" id="courseFile" class="form-control"
                                        accept=".json" required>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-danger">Import Course Data</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Courses Section -->
                    <div class="card-body w-100 mt-3">
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
                                        <div class="d-flex">
                                            <a href="{{ route('course-details', ['courseCode' => $course->course_code]) }}"
                                                class="btn btn-primary btn-sm me-2">Go to Course</a>
                                            @if (Auth::guard('teacher')->check())
                                                <form
                                                    action="{{ route('delete-course', ['courseCode' => $course->course_code]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            @endif
                                        </div>
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

@section('toasts')
    @include('components.toasts')
@endsection
