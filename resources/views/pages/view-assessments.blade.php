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
                <div class="d-flex gap-4">
                    <a href="{{ route('course-details', ['courseCode' => $course->course_code]) }}"
                        class="btn btn-sm h-25 btn-warning">Back</a>
                    <h3>{{ $course->course_code }} {{ $course->name }}</h3>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Assessment Details Card -->
                <div class="card my-3">
                    <div class="card-header cs-red text-white d-flex justify-content-between align-items-center">
                        <h4>Assessment Details</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Assessment Title:</strong> {{ $assessment->title }}</p>
                        <p><strong>Instructions:</strong> {{ $assessment->instruction }}</p>
                        <p><strong>Number of Reviews:</strong> {{ $assessment->num_reviews }}</p>
                        <p><strong>Maximum Score:</strong> {{ $assessment->max_score }}</p>
                        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($assessment->due_date)->format('d F, Y') }}</p>
                        <p><strong>Due Time:</strong> {{ \Carbon\Carbon::parse($assessment->due_time)->format('h:i A') }}</p>
                        <p><strong>Review Type:</strong> {{ $assessment->type }}</p>
                    </div>
                </div>

                <!-- Reviews Given by the Student -->
                <div class="card my-3">
                    <div class="card-header cs-red text-white">
                        <h4>Your Reviews Sent for This Assessment</h4>
                    </div>
                    <div class="card-body">
                        @if($reviewsSent->isEmpty())
                            <p>You have not submitted any reviews for this assessment yet.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($reviewsSent as $review)
                                    <li class="list-group-item">
                                        <p><strong>Review for:</strong> {{ $review->reviewee->name }}</p>
                                        <p><strong>Rating:</strong> {{ $review->rating }}</p>
                                        <p><strong>Comments:</strong> {{ $review->review_text }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Reviews Received by the Student -->
                <div class="card my-3">
                    <div class="card-header cs-red text-white">
                        <h4>Reviews You Have Received for This Assessment</h4>
                    </div>
                    <div class="card-body">
                        @if($reviewsReceived->isEmpty())
                            <p>No reviews have been submitted for you yet.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($reviewsReceived as $review)
                                    <li class="list-group-item">
                                        <p><strong>Reviewer:</strong> {{ $review->reviewer->name }}</p>
                                        <p><strong>Rating:</strong> {{ $review->rating }}</p>
                                        <p><strong>Comments:</strong> {{ $review->review_text }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Enrolled Students Section -->
                <div id="studentList" class="p-0">
                    <div class="card my-3">
                        <div class="card-header cs-red text-white">
                            <h4>Enrolled Students</h4>
                        </div>
                        <div>
                            <input type="text" id="studentSearch" class="form-control"
                                placeholder="Search student by name or sNumber">
                        </div>
                        <div class="card-body p-0">
                        
                                <ul class="list-group p-0">
                                    @foreach ($students as $student)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <p><strong>Name:</strong> {{ $student->name }}</p>
                                                <p><strong>Student Number:</strong> {{ $student->snumber }}</p>
                                            </div>
                                            @if (Auth::guard('web')->check())
                                                <!-- Button for Add Peer Review -->
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
                                <!-- Pagination Links -->
                                <div class="mt-3 d-flex justify-content-center">
                                    {!! $students->appends(['showStudents' => request('showStudents')])->links('pagination::bootstrap-4') !!}
                                </div>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
