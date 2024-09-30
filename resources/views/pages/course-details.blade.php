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
                <div
                    class="card-header
            d-flex
            justify-content-between
            align-items-center
            bg-danger
            text-white">
                    <h3>{{ $course->course_code }} {{ $course->name }}</h3>
                    <a href="{{ route('add-assessment', ['courseCode' => $course->course_code]) }}"
                        class="btn btn-warning btn-sm h-25">Add Assessment</a>
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
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $assessment->title }}</span>
                                    <div>
                                        <span class="badge bg-danger text-white">Due Date:
                                            {{ $assessment->due_date }}</span>
                                        <a href="{{ route('assessment-details', [
                                            'courseCode' => $course->course_code,
                                            'assessmentId' => $assessment->id,
                                        ]) }}"
                                            class="btn btn-primary btn-sm">View Details</a>
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
                <div class="card-header bg-danger text-white">
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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-danger text-white">
                    <h4>Enrolled Students</h4>
                    <button id="addStudentBtn" class="btn btn-warning btn-sm h-25" data-bs-toggle="modal"
                        data-bs-target="#addStudentModal">Add Student</button>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse ($course->students as $student)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $student->name }}</span>
                                <span>Student Number: {{ $student->snumber }}</span>
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

<!-- Add Student Modal -->
<div id="addStudentModal" class="modal fade" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="addStudentModalLabel">Enroll Existing Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @forelse ($unenrolledStudents as $student)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $student->name }} ({{ $student->snumber }})</span>
                            <form action="{{ route('enroll-student', ['courseCode' => $course->course_code, 'studentId' => $student->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">Enroll</button>
                            </form>
                        </li>
                    @empty
                        <li class="list-group-item">All students are already enrolled in this course.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

@if (session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
            errorToast.show();
        });
    </script>
@endif

@if (session('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif
