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
                <h3>{{ $course->course_code }} {{ $course->name }}</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Assessment Details -->
                <div class="card my-3">
                    <div class="card-header cs-red text-white">
                        <h3>Peer Review</h3>
                    </div>
                    <div class="card-body">
                        <form
                            id="reviewForm"
                            action="{{ route('store-review', [
                                'courseCode' => $course->course_code,
                                'studentId' => $student->id,
                                'assessmentId' => $assessment->id,
                            ]) }}"
                            method="POST">
                            @csrf

                            <!-- Guidance Section -->
                            <div class="mb-4">
                                <h5>Guidelines for Writing a Good Review</h5>
                                <ul>
                                    <li>Provide constructive feedback focusing on both strengths and areas of improvement.</li>
                                    <li>Be specific, clear, and use examples if possible.</li>
                                    <li>Avoid using harsh or discouraging language.</li>
                                </ul>
                            </div>

                            <!-- Review Text Area with Word Count and Quality Indicator -->
                            <div class="form-group mb-4">
                                <h5>Reviewee: {{ $student->name }}</h5>
                                <label for="review">Your Review (at least 5 words)
                                    <span id="reviewQualityIndicator" class="ms-2 text-muted">
                                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> - Too Short
                                    </span>
                                </label>
                                <textarea id="review" name="review" class="form-control mt-2" rows="4" placeholder="Write your review here..." minlength="5">{{ old('review') }}</textarea>
                                <small id="wordCountIndicator" class="text-muted">Word Count: 0</small>
                            </div>

                            <!-- Positive Feedback Section -->
                            <div class="form-group mb-4">
                                <label for="positive-feedback">What did the student do well?</label>
                                <textarea id="positive-feedback" name="positive_feedback" class="form-control mt-2" rows="3" placeholder="Write what was done well...">{{ old('positive_feedback') }}</textarea>
                            </div>

                            <!-- Improvement Feedback Section -->
                            <div class="form-group mb-4">
                                <label for="improvement-feedback">What could be improved?</label>
                                <textarea id="improvement-feedback" name="improvement_feedback" class="form-control mt-2" rows="3" placeholder="Write what could be improved...">{{ old('improvement_feedback') }}</textarea>
                            </div>

                            <!-- Rating Input with Descriptions -->
                            <div class="form-group mb-4">
                                <label for="rating">Rating (1 to 5)</label>
                                <div class="d-flex justify-content-between">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label" for="rating{{ $i }}">{{ $i }}
                                                @if ($i == 1) - Poor
                                                @elseif ($i == 2) - Needs Improvement
                                                @elseif ($i == 3) - Average
                                                @elseif ($i == 4) - Good
                                                @elseif ($i == 5) - Excellent
                                                @endif
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Examples of Good Reviews -->
                            <div class="mb-4">
                                <h5>Examples of Good Reviews</h5>
                                <ul class="list-group">
                                    <li class="list-group-item">"This presentation was well-organized, and the main points were clearly articulated. To make it even better, consider adding more real-world examples."</li>
                                    <li class="list-group-item">"You did a great job explaining the topic, but you could improve by maintaining eye contact with the audience."</li>
                                    <li class="list-group-item">"The group worked well together, and the project was very informative. Including a Q&A session would have made it even more engaging."</li>
                                </ul>
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

@section('toasts')
    @include('components.toasts')
@endsection
