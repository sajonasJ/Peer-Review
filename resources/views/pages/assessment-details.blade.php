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

        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Assessment Details Card -->
                <div class="card my-3">
                    <div class="card-header cs-red text-white d-flex justify-content-between align-items-center">
                        <h4>Assessment Details</h4>
                        <div>
                            @if (Auth::guard('teacher')->check())
                                @if ($reviewCount == 0)
                                    <a href="{{ route('edit-assessment', [
                                        'courseCode' => $course->course_code,
                                        'assessmentId' => $assessment->id,
                                    ]) }}"
                                        class="btn btn-sm btn-csw10 btn-warning">Edit</a>
                                @else
                                    <button class="btn btn-sm bg-dark" disabled>Edit (Reviews Exist)</button>
                                @endif
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

                <!-- Student List Section (Initially Hidden) -->
                <div id="studentList" class="p-0" style="display: none;">
                    <div class="card my-3">
                        <div class="card-header cs-red text-white">
                            <h4>Enrolled Students</h4>
                        </div>
                        <div>
                            <input type="text" id="studentSearch" class="form-control"
                                placeholder="Search student by name or sNumber">
                        </div>
                        <div class="card-body p-0">
                            @if ($course->students->isEmpty())
                                <p>No students enrolled in this course yet.</p>
                            @else
                                <ul class="list-group p-0">
                                    @foreach ($course->students as $student)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <p><strong>Name:</strong> {{ $student->name }}</p>
                                                <p><strong>Student Number:</strong> {{ $student->snumber }}</p>
                                            </div>
                                            <!-- Buttons for Add Peer Review and View Student Details -->
                                            <div class="d-flex gap-2">
                                                @if (Auth::guard('web')->check())
                                                    <a href="{{ route('add-review', [
                                                        'courseCode' => $course->course_code,
                                                        'studentId' => $student->id,
                                                        'assessmentId' => $assessment->id,
                                                    ]) }}"
                                                        class="btn btn-primary btn-sm">Add Peer Review</a>
                                                @endif
                                                @if (Auth::guard('teacher')->check())
                                                    <a href="{{ route('assessment-details', [
                                                        'courseCode' => $course->course_code,
                                                        'assessmentId' => $assessment->id,
                                                    ]) }}?studentId={{ $student->id }}"
                                                        class="btn btn-primary btn-sm btn-csw10">View</a>
                                                @endif

                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($selectedStudent)
                    <div class="card my-3">
                        <div class="card-header cs-red text-white d-flex justify-content-between align-items-center">
                            <h4>Reviews Received by {{ $selectedStudent->name }}</h4>
                            <button id="toggleAssignReviewerList" class="btn btn-sm btn-csw10 btn-warning">Add
                                Reviewer</button>
                        </div>
                        <div class="card-body p-0">
                            @if ($studentReviewsReceived->isEmpty())
                                <p class="p-2">No reviews received yet.</p>
                            @else
                                <ul class="list-group">
                                    @foreach ($studentReviewsReceived as $review)
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
                    <!-- Add Reviewer List Section -->

                    <div id="assignReviewerList" class="card p-0" style="display: none;">
                        <div class="card-header cs-red text-white">
                            <h4>Assign Reviewer</h4>
                        </div>
                        <div>
                            <input type="text" id="studentSearchAssignReviewer" class="form-control" placeholder="Search student by name or sNumber">

                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group p-0">
                                @foreach ($course->students as $student)
                                    @if ($selectedStudent == null || $selectedStudent->id !== $student->id)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <p><strong>Name:</strong> {{ $student->name }}</p>
                                                <p><strong>Student Number:</strong> {{ $student->snumber }}</p>
                                            </div>
                                            <form
                                                action="{{ route('assign-reviewer', ['courseCode' => $course->course_code, 'assessmentId' => $assessment->id]) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                <button type="submit" class="btn btn-primary btn-sm disabled">Add
                                                    Reviewer</button>
                                            </form>

                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card my-3">
                        <div class="card-header cs-red text-white d-flex justify-content-between align-items-center">
                            <h4>Reviews Sent by {{ $selectedStudent->name }}</h4>
                            <button id="toggleAssignRevieweeList" class="btn btn-sm btn-csw10 btn-warning">Add
                                Reviewee</button>
                        </div>
                        <div class="card-body p-0">
                            @if ($studentReviewsSent->isEmpty())
                                <p class="p-2">No reviews sent yet.</p>
                            @else
                                <ul class="list-group">
                                    @foreach ($studentReviewsSent as $review)
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



                <!-- Add Reviewee List Section -->
                <div id="assignRevieweeList" class="card p-0 my-3" style="display: none;">
                    <div class="card-header cs-red text-white">
                        <h4>Assign Reviewee</h4>
                    </div>
                    <div>
                        <input type="text" id="studentSearchAssignReviewee" class="form-control" placeholder="Search student by name or sNumber">

                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group p-0">
                            @foreach ($course->students as $student)
                                @if ($selectedStudent == null || $selectedStudent->id !== $student->id)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <p><strong>Name:</strong> {{ $student->name }}</p>
                                            <p><strong>Student Number:</strong> {{ $student->snumber }}</p>
                                        </div>
                                        <form
                                            action="{{ route('assign-reviewee', ['courseCode' => $course->course_code, 'assessmentId' => $assessment->id]) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm disabled">Add
                                                Reviewee</button>
                                        </form>

                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
