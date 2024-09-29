@extends('layouts.master')

@section('title', 'Submit Peer Review')

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
            <div class="col-md-10">
                <!-- Assessment Details -->
                <div class="card border-danger mb-4">
                    <div class="card-header bg-danger text-white">
                        <h3>Assessment Title: Peer Review for Week 1</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Instructions:</strong> Please complete the peer review for your selected peers. You need to submit at least 5 words in your review.</p>
                        <p><strong>Number of Reviews Required:</strong> 3</p>
                        <p><strong>Due Date:</strong> 2024-10-10</p>
                    </div>
                </div>

                <!-- Submitted Reviews Section -->
                <div class="card border-danger mb-4">
                    <div class="card-header bg-danger text-white">
                        <strong>Submitted Reviews:</strong>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <!-- Example Submitted Reviews -->
                            <li class="list-group-item">Review for John Doe: "Great effort on the project, but more details needed in the analysis."</li>
                            <li class="list-group-item">Review for Jane Smith: "Thorough explanation of the concepts, but could improve on presentation."</li>
                        </ul>
                    </div>
                </div>

                <!-- Add Peer Review Button -->
                <button class="btn btn-danger" id="addReviewBtn">Add Peer Review</button>

                <!-- Student List (Initially Hidden) -->
                <div id="studentList" class="mt-4" style="display: none;">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h4>Select a Student to Review</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="studentListItems">
                                <!-- List of students with Add Review button -->
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Review Form (Initially Hidden) -->
                <div id="reviewForm" class="mt-4" style="display: none;">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h4>Submit Peer Review for <span id="selectedStudent"></span></h4> <!-- Student's name -->
                        </div>
                        <div class="card-body">
                            <form>
                                <!-- Review Text Area -->
                                <div class="form-group mb-4">
                                    <label for="review">Your Review (at least 5 words)</label>
                                    <textarea id="review" class="form-control" rows="4" placeholder="Write your review here..." minlength="5" required></textarea>
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
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

<!-- Move the script here, just before the closing body tag -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const students = ['John Doe', 'Jane Smith', 'Alice Johnson', 'Mark Brown', 'Emily White']; // Sample students

        const addReviewBtn = document.getElementById('addReviewBtn');
        const studentList = document.getElementById('studentList');
        const studentListItems = document.getElementById('studentListItems');
        const reviewForm = document.getElementById('reviewForm');
        const selectedStudentSpan = document.getElementById('selectedStudent');

        // Show student list when "Add Peer Review" button is clicked
        addReviewBtn.addEventListener('click', function() {
            studentList.style.display = 'block';
            reviewForm.style.display = 'none'; // Hide review form if it's already visible
            studentListItems.innerHTML = ''; // Clear any previous student list

            // Populate the student list
            students.forEach(student => {
                const listItem = document.createElement('li');
                listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

                const studentName = document.createElement('span');
                studentName.textContent = student;

                const addButton = document.createElement('button');
                addButton.classList.add('btn', 'btn-outline-danger', 'btn-sm');
                addButton.textContent = 'Add Review';
                addButton.addEventListener('click', function() {
                    // Set the selected student's name in the form
                    selectedStudentSpan.textContent = student;
                    reviewForm.style.display = 'block'; // Show the review form
                    studentList.style.display = 'none'; // Hide the student list
                });

                listItem.appendChild(studentName);
                listItem.appendChild(addButton);
                studentListItems.appendChild(listItem);
            });
        });
    });
</script>


@section('content')
    <div class="container m-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Assessment Details -->
                <div class="card border-danger mb-4">
                    <div class="card-header bg-danger text-white">
                        <h3>Submit Peer Review for {{ $student->name }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store-review', ['courseCode' => $course->course_code, 'studentId' => $student->id]) }}" method="POST">
                            @csrf
                            <!-- Review Text Area -->
                            <div class="form-group mb-4">
                                <label for="review">Your Review (at least 5 words)</label>
                                <textarea id="review" name="review" class="form-control" rows="4" placeholder="Write your review here..." minlength="5" required></textarea>
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
