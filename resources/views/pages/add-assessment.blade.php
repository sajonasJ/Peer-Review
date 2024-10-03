@extends('layouts.master')

@section('title', isset($assessment) ? 'Edit Peer Review Assessment' : 'Add Peer Review Assessment')

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
                <a href="{{ isset($assessment)
                    ? route('assessment-details', [
                        'courseCode' => $course->course_code,
                        'assessmentId' => $assessment->id,
                    ])
                    : route('course-details', [
                        'courseCode' => $course->course_code,
                    ]) }}"
                    class="btn btn-warning btn-sm h-25">Back</a>

                <h3>Course: {{ $course->course_code }} - {{ $course->name }}</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card my-3 border-danger">
                    <div class="card-header cs-red text-white">
                        <h3>{{ isset($assessment) ? 'Edit' : 'Add' }} Peer Review Assessment</h3>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($assessment)
                                ? route('update-assessment', [
                                    'courseCode' => $course->course_code,
                                    'assessmentId' => $assessment->id,
                                ])
                                : route('store-assessment', [
                                    'courseCode' => $course->course_code,
                                ]) }}"
                            method="POST">
                            @csrf
                            <!-- Assessment Title -->
                            <div class="form-group mb-3">
                                <label for="title">Assessment Title</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Enter title (max 20 characters)" maxlength="20"
                                    value="{{ old('title', isset($assessment) ? $assessment->title : '') }}">
                                @error('title')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Assessment Instructions -->
                            <div class="form-group mb-3">
                                <label for="instructions">Instructions</label>
                                <textarea id="instructions" name="instructions" class="form-control" rows="4" placeholder="Enter instructions">{
                                    { old('instructions', isset($assessment) ? $assessment->instruction : '') }}</textarea>
                                @error('instructions')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Number of Reviews -->
                            <div class="form-group mb-3">
                                <label for="num_reviews">Number of Reviews</label>
                                <input type="number" id="num_reviews" name="num_reviews" class="form-control"
                                    min="1"
                                    value="{{ old('num_reviews', isset($assessment) ? $assessment->num_reviews : '1') }}">
                                @error('num_reviews')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Maximum Score -->
                            <div class="form-group mb-3">
                                <label for="max_score">Maximum Score</label>
                                <input type="number" id="max_score" name="max_score" class="form-control" min="1"
                                    max="100"
                                    value="{{ old('max_score', isset($assessment) ? $assessment->max_score : '100') }}">
                                @error('max_score')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Due Date with Inline Calendar -->
                            <div class="form-group mb-3">
                                <label for="due_date">Due Date</label>
                                <input id="due_date_input" name="due_date" class="form-control" type="text"
                                    placeholder="Select Date.." readonly="readonly"
                                    value="{{ old('due_date', isset($assessment) ? $assessment->due_date : '') }}">
                                @error('due_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Due Time -->
                            <div class="form-group mb-3">
                                <label for="due_time">Due Time</label>
                                <input type="text" id="due_time" name="due_time" class="form-control"
                                    value="{{ old('due_time', isset($assessment) ? $assessment->due_time : '') }}">
                                @error('due_time')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Peer Review Type -->
                            <div class="form-group mb-4">
                                <label for="review_type">Peer Review Type</label>
                                <select id="review_type" name="review_type" class="form-control">
                                    <option value="" disabled {{ !isset($assessment) ? 'selected' : '' }}>Select
                                        Review Type</option>
                                    <option value="student-select"
                                        {{ old('review_type', isset($assessment) && $assessment->type === 'student-select' ? 'selected' : '') }}>
                                        Student-Select</option>
                                    <option value="teacher-assign"
                                        {{ old('review_type', isset($assessment) && $assessment->type === 'teacher-assign' ? 'selected' : '') }}>
                                        Teacher-Assign</option>
                                </select>
                                @error('review_type')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex d-grid d-md-flex justify-content-md-evenly mb-3">
                                <button type="submit"
                                    class="btn btn-primary btn-csw10">{{ isset($assessment) ? 'Save' : 'Add' }}</button>

                                <!-- Updated Cancel Button Redirect -->
                                <a href="{{ isset($assessment)
                                    ? route('assessment-details', [
                                        'courseCode' => $course->course_code,
                                        'assessmentId' => $assessment->id,
                                    ])
                                    : route('course-details', [
                                        'courseCode' => $course->course_code,
                                    ]) }}"
                                    class="btn btn-danger btn-csw10">Cancel</a>
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
