@extends('layouts.master')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('title', 'Course Details')

@section('header')
    @include('layouts.header')
@endsection
@section('nav')
    @include('layouts.nav')
@endsection


@section('content')
    <main class="container mt-3 mb-3">
        <!-- Content Sections -->
        <div id="course-overview" class="tab-content active-content">
            <!-- Course Overview Content Here -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center cs-red text-white">
                    <h3>{{ $course->course_code }} {{ $course->name }}</h3>
                    @if (Auth::guard('teacher')->check())
                        <a href="{{ route('add-assessment', ['courseCode' => $course->course_code]) }}"
                            class="btn btn-warning btn-sm h-25">Add Assessment</a>
                    @endif
                </div>

                <!-- Assessments Section -->
                <div class="card-body mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger">Peer Review Assessments</h4>
                    </div>
                    @if ($course->assessments->isEmpty())
                        <p>No assessments available. Please add one to get started.</p>
                    @else
                        <ul class="list-group mt-3">
                            @foreach ($course->assessments as $assessment)
                                <li class="list-group-item d-flex flex-row justify-content-between align-items-center">
                                    <span>{{ $assessment->title }}</span>
                                    <div class="col-lg-3 d-flex justify-content-between align-items-center">
                                        <span><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($assessment->due_date)->format('d F, Y') }}</span>
                                        <a href="{{ route('assessment-details', [
                                            'courseCode' => $course->course_code,
                                            'assessmentId' => $assessment->id,
                                        ]) }}" class="btn btn-primary mx-1 btn-sm">View Details</a>
                                    </div>
                                    
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>


        <div id="teaching-staff" class="tab-content">
            <!-- Teaching Staff Content Here -->
            <div class="card">
                <div class="card-header cs-red text-white">
                    <h4>Instructors</h4>
                </div>
                <div class="card-body">
                    <ul class="list-style">
                        @forelse ($course->teachers as $teacher)
                            <li>{{ $teacher->name }}</li>
                        @empty
                            <li>No instructors assigned yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Students Section -->
        <div id="students" class="tab-content">
            <!-- Enrolled Students Card (Default View) -->
            <div id="enrolledStudentsCard" class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center cs-red text-white">
                    <h4>Enrolled Students</h4>
                    @if (Auth::guard('teacher')->check())
                        <button id="addStudentBtn" class="btn btn-warning btn-csw10 btn-sm">Add Student</button>
                    @endif
                </div>

                <div class="card-body p-0">
                    <ul class="list-group">
                        @forelse ($course->students as $student)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="student-info">
                                    <p><strong>Name:</strong> {{ $student->name }}</p>
                                    <p><strong>Student Number:</strong> {{ $student->snumber }}</p>
                                </div>
                                
                            </li>
                        @empty
                            <li class="list-group-item">No students enrolled yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Enroll Existing Student Card (Hidden by Default) -->
            <div id="enrollStudentCard" class="card mt-4" style="display: none;">
                <div class="card-header d-flex justify-content-between align-items-center cs-red text-white">
                    <h4>Enroll Existing Student</h4>
                    <button id="backToEnrolledBtn" class="btn btn-warning btn-csw10 btn-sm">Back</button>
                </div>
                <div class="card-body p-0">
                    <!-- Search Bar -->
                    <div>
                        <input type="text" id="studentSearch" class="form-control"
                            placeholder="Search student by name or sNumber">
                    </div>
                    <!-- Student List -->
                    <ul class="list-group" id="studentList">
                        @forelse ($unenrolledStudents as $student)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <p><strong>Name:</strong> {{ $student->name }}</p>
                                    <p><strong>Student Number:</strong> {{ $student->snumber }}</p>
                                </div>
                                <form
                                    action="{{ route('enroll-student', ['courseCode' => $course->course_code, 'studentId' => $student->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-csw10 btn-sm">Enroll</button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item">All students are already enrolled in this course.</li>
                        @endforelse
                    </ul>
                </div>
            </div>


        </div>

    </main>
@endsection


@section('footer')
    @include('layouts.footer')
@endsection


@section('toasts')
    @include('components.toasts')
@endsection
