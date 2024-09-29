@extends('layouts.master')

@section('title', 'Submit Peer Review')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('header')
    @include('layouts.header')
@endsection


@section('content')
    <div class="container-fluid p-0">
        <div class="course-title px-3 py-2">
            <div class="d-flex gap-4 justify-content-start align-items-center">
                <a href="{{ route('course-details', ['courseCode' => $course->course_code]) }}"
                    class="btn btn-sm h-25 btn-warning">Back</a>
                <h3>Course: {{ $course->course_code }} - {{ $course->name }}</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Assessment Details -->
                <div class="card my-3">
                    <div class="card-header bg-danger text-white">
                        <h3>Peer Review</h3>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ route('store-review', [
                                'courseCode' => $course->course_code,
                                'studentId' => $student->id,
                                'assessmentId' => $assessment->id,
                            ]) }}"
                            method="POST">
                            @csrf
                            <!-- Review Text Area -->
                            <div class="form-group mb-4">
                                <h5>Reviewee: {{ $student->name }}</h5>
                                <label for="review">Your Review (at least 5 words)</label>
        
                                <textarea id="review" name="review" class="form-control mt-2" rows="4" placeholder="Write your review here..."
                                    minlength="5" required></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger">Submit Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
