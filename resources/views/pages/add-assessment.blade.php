@extends('layouts.master')

@section('title', isset($assessment) ? 'Edit Peer Review Assessment' : 'Add Peer Review Assessment')

@section('nav-top')
    @include('components.nav-top')
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('nav')
    @include('layouts.nav')
@endsection

@section('content')
    <div class="container m-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Back Button on Top Left -->
                <div class="d-flex justify-content-start mb-3">
                    <button onclick="history.back()" class="btn btn-outline-danger">Back</button>
                </div>

                <!-- Form for Adding Peer Review Assessment -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3>{{ isset($assessment) ? 'Edit' : 'Add' }} Peer Review Assessment</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($assessment) ? route('store-assessment', ['courseCode' => $course->course_code]) : route('store-assessment', ['courseCode' => $course->course_code]) }}" method="POST">
                            @csrf
                            <!-- Assessment Title -->
                            <div class="form-group mb-3">
                                <label for="title">Assessment Title</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Enter title (max 20 characters)" maxlength="20" required
                                    value="{{ old('title', isset($assessment) ? $assessment->title : '') }}">
                            </div>

                            <!-- Assessment Instructions -->
                            <div class="form-group mb-3">
                                <label for="instructions">Instructions</label>
                                <textarea id="instructions" name="instructions" class="form-control" rows="4" placeholder="Enter instructions"
                                    required>{{ old('instructions', isset($assessment) ? $assessment->instruction : '') }}</textarea>
                            </div>

                            <!-- Number of Reviews -->
                            <div class="form-group mb-3">
                                <label for="num_reviews">Number of Reviews</label>
                                <input type="number" id="num_reviews" name="num_reviews" class="form-control"
                                    min="1" required value="{{ old('num_reviews', isset($assessment) ? $assessment->num_reviews : '1') }}">
                            </div>

                            <!-- Maximum Score -->
                            <div class="form-group mb-3">
                                <label for="max_score">Maximum Score</label>
                                <input type="number" id="max_score" name="max_score" class="form-control" min="1"
                                    max="100" required value="{{ old('max_score', isset($assessment) ? $assessment->max_score : '100') }}">
                            </div>

                            <!-- Due Date with Inline Calendar -->
                            <div class="form-group mb-3">
                                <label for="due_date">Due Date</label>
                                <input id="due_date_input" name="due_date" class="form-control" type="text" placeholder="Select Date.."
                                    readonly="readonly" value="{{ old('due_date', isset($assessment) ? $assessment->due_date : '') }}">
                            </div>

                            <!-- Due Time -->
                            <div class="form-group mb-3">
                                <label for="due_time">Due Time</label>
                                <input type="text" id="due_time" name="due_time" class="form-control" required
                                    value="{{ old('due_time', isset($assessment) ? $assessment->due_time : '') }}">
                            </div>

                            <!-- Peer Review Type -->
                            <div class="form-group mb-4">
                                <label for="review_type">Peer Review Type</label>
                                <select id="review_type" name="review_type" class="form-control" required>
                                    <option value="" disabled {{ !isset($assessment) ? 'selected' : '' }}>Select Review Type</option>
                                    <option value="student-select" {{ old('review_type', isset($assessment) && $assessment->type === 'student-select' ? 'selected' : '') }}>Student-Select</option>
                                    <option value="teacher-assign" {{ old('review_type', isset($assessment) && $assessment->type === 'teacher-assign' ? 'selected' : '') }}>Teacher-Assign</option>
                                </select>
                            </div>

                            <!-- Add Assessment Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger">{{ isset($assessment) ? 'Update' : 'Add' }} Assessment</button>
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
