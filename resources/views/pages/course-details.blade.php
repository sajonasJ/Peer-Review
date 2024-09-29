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
                <div class="card-header d-flex justify-content-between align-items-center bg-danger text-white">
                    <h3>{{ $course->course_code }} - {{ $course->name }}</h3>
                    <a href="{{ route('add-assessment', ['courseCode' => $course->course_code]) }}"
                        class="btn btn-warning btn-sm h-25">Add
                        Assessment</a>
                </div>

                <!-- Assessments Section -->
                <div class="card-body mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger">Peer Review Assessments</h4>

                    </div>
                    <ul class="list-group mt-3">
                        @foreach ($course->assessments as $assessment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $assessment->title }}</span>
                                <div>
                                    <span class="badge bg-danger text-white">Due Date: {{ $assessment->due_date }}</span>
                                    <a href="{{ route('assessment-details', ['courseCode' => $course->course_code, 'assessmentId' => $assessment->id]) }}"
                                        class="btn btn-primary btn-sm ">View Details</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div id="teaching-staff" class="tab-content">
            <!-- Teaching Staff Content Here -->
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4>Instructors:</h4>
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

        <div id="students" class="tab-content">
            <!-- Students Content Here -->
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4>Enrolled Students:</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse ($course->students as $student)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $student->name }}</span>
                                <!-- Add Review Button -->
                                <a href="{{ route('add-review', ['courseCode' => $course->course_code, 'studentId' => $student->id]) }}"
                                    class="btn btn-outline-danger btn-sm">Add Review</a>
                            </li>
                        @empty
                            <li class="list-group-item">No students enrolled yet.</li>
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
