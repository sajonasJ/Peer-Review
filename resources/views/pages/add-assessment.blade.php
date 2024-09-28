@extends('layouts.master')

@section('title', 'Add Peer Review Assessment')

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
                <!-- Form for Adding Peer Review Assessment -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3>Add Peer Review Assessment</h3>
                    </div>
                    <div class="card-body">
                        <form>
                            <!-- Assessment Title -->
                            <div class="form-group mb-3">
                                <label for="title">Assessment Title</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Enter title (max 20 characters)" maxlength="20" required>
                            </div>

                            <!-- Assessment Instructions -->
                            <div class="form-group mb-3">
                                <label for="instructions">Instructions</label>
                                <textarea id="instructions" name="instructions" class="form-control" rows="4" placeholder="Enter instructions"
                                    required></textarea>
                            </div>

                            <!-- Number of Reviews -->
                            <div class="form-group mb-3">
                                <label for="num_reviews">Number of Reviews</label>
                                <input type="number" id="num_reviews" name="num_reviews" class="form-control"
                                    min="1" value="1" required>
                            </div>

                            <!-- Maximum Score -->
                            <div class="form-group mb-3">
                                <label for="max_score">Maximum Score</label>
                                <input type="number" id="max_score" name="max_score" class="form-control" min="1"
                                    max="100" value="100" required>
                            </div>

                            <!-- Due Date with Inline Calendar -->
                            <div class="form-group mb-3">
                                <label for="due_date">Due Date</label>
                                <input id="due_date_input" class="form-control" type="text" placeholder="Select Date.."
                                    readonly="readonly">

                                <!-- Inline Flatpickr Calendar -->
                                <div id="due_date" class="flatpickr"></div>
                            </div>

                            <!-- Flatpickr Time Picker for Due Time (12-hour format with AM/PM) -->
                            <div class="form-group mb-3">
                                <label for="due_time">Due Time</label>
                                <input type="text" id="due_time" name="due_time" class="form-control" required>
                            </div>

                            <!-- Peer Review Type -->
                            <div class="form-group mb-4">
                                <label for="review_type">Peer Review Type</label>
                                <select id="review_type" name="review_type" class="form-control" required>
                                    <option value="" disabled selected>Select Review Type</option>
                                    <option value="student-select">Student-Select</option>
                                    <option value="teacher-assign">Teacher-Assign</option>
                                </select>
                            </div>

                            <!-- Workshop Week Dropdown -->
                            <div class="form-group mb-4">
                                <label for="workshop_week">Workshop Week</label>
                                <select id="workshop_week" name="workshop_week" class="form-control" required>
                                    <option value="" disabled selected>Select Workshop Week</option>
                                    @for ($week = 1; $week <= 13; $week++)
                                        <option value="Week {{ $week }}">Week {{ $week }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Add Assessment Button -->
                            <div class="text-center">
                                <a href="#" class="btn btn-danger disabled">Add Assessment</a>
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
