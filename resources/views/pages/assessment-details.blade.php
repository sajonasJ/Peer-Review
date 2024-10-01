@extends('layouts.master')

@section('title', 'Assessment Details')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="course-title px-3 py-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex gap-4 ">
                    <a href="{{ route('course-details', ['courseCode' => $course->course_code]) }}"
                        class="btn btn-sm h-25 btn-warning">Back</a>
                    <h3>{{ $course->course_code }} {{ $course->name }}</h3>
                </div>
                <!-- Button to toggle student list -->
                <div>
                    @if ($assessment->type === 'student-select')
                        <button id="toggleStudentList" class="btn btn-sm btn-csw10 btn-danger">Show Students</button>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>Show Students (Disabled)</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Student List Section (Initially Hidden) -->
        <div id="studentList" class="p-3" style="display: none;">
            <div class="card">
                <div class="card-header cs-red text-white">
                    <h4>Enrolled Students</h4>
                </div>
                <div class="card-body">
                    @if ($course->students->isEmpty())
                        <p>No students enrolled in this course yet.</p>
                    @else
                    <ul class="list-group">
                        @foreach ($course->students as $student)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <p><strong>Name:</strong> {{ $student->name }}</p>
                                    <p><strong>Student Number:</strong> {{ $student->snumber }}</p>
                                </div>
                                <!-- Add Peer Review Button - Only visible if the logged-in user is a student -->
                                @if (Auth::guard('web')->check())
                                    <a href="{{ route('add-review', [
                                        'courseCode' => $course->course_code,
                                        'studentId' => $student->id,
                                        'assessmentId' => $assessment->id,
                                    ]) }}"
                                        class="btn btn-primary btn-sm">Add Peer Review</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    
                    @endif
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Assessment Details Card -->
                <div class="card my-3">
                    <div class="card-header cs-red text-white d-flex justify-content-between align-items-center">
                        <h4>Assessment Details</h4>
                        <div>
                            @if (Auth::guard('teacher')->check())
                                <a href="{{ route('edit-assessment', [
                                    'courseCode' => $course->course_code,
                                    'assessmentId' => $assessment->id,
                                ]) }}"
                                    class="btn btn-sm btn-csw10 btn-warning">Edit</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <p><strong>Assessment Title:</strong> {{ $assessment->title }}</p>
                        <p><strong>Instructions:</strong> {{ $assessment->instruction }}</p>
                        <p><strong>Number of Reviews:</strong> {{ $assessment->num_reviews }}</p>
                        <p><strong>Maximum Score:</strong> {{ $assessment->max_score }}</p>
                        <p><strong>Due Date:</strong> {{ $assessment->due_date }}</p>
                        <p><strong>Due Time:</strong> {{ $assessment->due_time }}</p>
                        <p><strong>Review Type:</strong> {{ $assessment->type }}</p>
                    </div>
                </div>

               <!-- Peer Reviews Received Card -->
@if (Auth::guard('web')->check())
<div class="card my-3">
    <div class="card-header cs-red text-white">
        <h4>Peer Reviews Received</h4>
    </div>
    <div class="card-body">
        @if ($reviewsReceived->isEmpty())
            <p>No peer reviews received yet.</p>
        @else
            <ul class="list-group">
                @foreach ($reviewsReceived as $review)
                    <li class="list-group-item">
                        <p><strong>Reviewer:</strong> {{ $review->reviewer->name }}</p>
                        <p><strong>Review Text:</strong> {{ $review->review_text }}</p>
                        <p><strong>Rating:</strong> {{ $review->rating }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endif

<!-- Peer Reviews Sent Card -->
@if (Auth::guard('web')->check())
<div class="card my-3">
    <div class="card-header cs-red text-white">
        <h4>Peer Reviews Sent</h4>
    </div>
    <div class="card-body">
        @if ($reviewsSent->isEmpty())
            <p>No peer reviews sent yet.</p>
        @else
            <ul class="list-group">
                @foreach ($reviewsSent as $review)
                    <li class="list-group-item">
                        <p><strong>Reviewee:</strong> {{ $review->reviewee->name }}</p>
                        <p><strong>Review Text:</strong> {{ $review->review_text }}</p>
                        <p><strong>Rating:</strong> {{ $review->rating }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endif

            </div>
        </div>
    </div>
@endsection



@section('footer')
    @include('layouts.footer')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleStudentList');
        const studentList = document.getElementById('studentList');

        toggleButton.addEventListener('click', function() {
            if (studentList.style.display === 'none') {
                studentList.style.display = 'block';
                toggleButton.textContent = 'Hide Students';
            } else {
                studentList.style.display = 'none';
                toggleButton.textContent = 'Show Students';
            }
        });
    });
</script>
