document.addEventListener("DOMContentLoaded", function () {
    // Update Greeting and Date
    const greetingElement = document.getElementById("greeting");
    const userNameElement = document.getElementById("userName");
    const dateElement = document.getElementById("currentDate");

    if (greetingElement && userNameElement && dateElement) {
        const userName = userNameElement.textContent.trim() || "User";

        function getGreeting() {
            const today = new Date();
            const hour = today.getHours();
            return hour >= 5 && hour < 12
                ? "Good Morning,"
                : hour >= 12 && hour < 17
                ? "Good Afternoon,"
                : "Good Evening,";
        }

        function getCurrentDate() {
            const today = new Date();
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            return today.toLocaleDateString("en-US", options);
        }

        greetingElement.innerHTML = `${getGreeting()}`;
        userNameElement.innerHTML = `${userName}`;
        dateElement.innerHTML = `Today's Date: ${getCurrentDate()}`;
    }

    // Initialize Flatpickr
    if (document.querySelector("#due_date_input")) {
        flatpickr("#due_date_input", {
            inline: true,
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            minDate: "today",
        });
    }

    if (document.querySelector("#due_time")) {
        flatpickr("#due_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false,
            minuteIncrement: 15,
            onReady: function (selectedDates, dateStr, instance) {
                if (!dateStr) {
                    instance.input.setAttribute("placeholder", "Select Time..");
                }
            },
            onChange: function (selectedDates, dateStr, instance) {
                instance.input.removeAttribute("placeholder");
            },
        });
    }

    // Tab Navigation
    const tabs = document.querySelectorAll(".nav-tab");
    const contents = document.querySelectorAll(".tab-content");

    if (tabs && contents) {
        tabs.forEach((tab) => {
            tab.addEventListener("click", function (event) {
                if (!tab.getAttribute("data-target")) {
                    return;
                }
                event.preventDefault();
                tabs.forEach((t) => t.classList.remove("active-tab"));
                contents.forEach((content) =>
                    content.classList.remove("active-content")
                );
                tab.classList.add("active-tab");
                document
                    .getElementById(tab.getAttribute("data-target"))
                    .classList.add("active-content");
            });
        });
    }

    // Toasts for Success/Error Messages
    const successToastEl = document.getElementById("successToast");
    const errorToastEl = document.getElementById("errorToast");

    if (successToastEl) {
        new bootstrap.Toast(successToastEl).show();
    }
    if (errorToastEl) {
        new bootstrap.Toast(errorToastEl).show();
    }

    // Student Search Filtering (only if elements are present)
    const addStudentBtn = document.getElementById("addStudentBtn");
    const backToEnrolledBtn = document.getElementById("backToEnrolledBtn");
    const enrolledStudentsCard = document.getElementById("enrolledStudentsCard");
    const enrollStudentCard = document.getElementById("enrollStudentCard");
    const searchInput = document.getElementById("studentSearch");
    const studentList = document.getElementById("studentList");

    if (
        addStudentBtn &&
        backToEnrolledBtn &&
        enrolledStudentsCard &&
        enrollStudentCard
    ) {
        // Show "Enroll Existing Student" card when "Add Student" button is clicked
        addStudentBtn.addEventListener("click", function () {
            enrolledStudentsCard.style.display = "none";
            enrollStudentCard.style.display = "block";
        });

        // Show "Enrolled Students" card when "Back" button is clicked
        backToEnrolledBtn.addEventListener("click", function () {
            enrollStudentCard.style.display = "none";
            enrolledStudentsCard.style.display = "block";
        });
    }

    if (studentList && searchInput) {
        const students = Array.from(studentList.querySelectorAll(".list-group-item"));

        // Filter students when typing in the search bar
        searchInput.addEventListener("input", function () {
            const filter = searchInput.value.toLowerCase();

            students.forEach(function (student) {
                const studentInfoElement = student.querySelector("div");
                if (studentInfoElement) {
                    const studentInfo = studentInfoElement.textContent.toLowerCase();
                    // Check if student info contains the filter value and adjust display accordingly
                    if (studentInfo.includes(filter)) {
                        student.classList.remove("hidden");
                    } else {
                        student.classList.add("hidden");
                    }
                }
            });
        });
    }

    // Reusable function to toggle visibility and update button text with persistent state in localStorage
    function toggleVisibility(button, element, showText, hideText) {
        button.addEventListener("click", function () {
            if (element.style.display === "none" || element.style.display === "") {
                element.style.display = "block";
                button.textContent = hideText;
                localStorage.setItem(button.id, "true");
            } else {
                element.style.display = "none";
                button.textContent = showText;
                localStorage.setItem(button.id, "false");
            }
        });
    }

    // Toggle Student List with persistent state using localStorage
    const toggleButton = document.getElementById("toggleStudentList");
    if (toggleButton && studentList) {
        const showStudents = localStorage.getItem("toggleStudentList");
        if (showStudents === "true") {
            studentList.style.display = "block";
            toggleButton.textContent = "Hide Students";
        } else {
            studentList.style.display = "none";
            toggleButton.textContent = "Show Students";
        }

        toggleVisibility(toggleButton, studentList, "Show Students", "Hide Students");
    }

    // Toggle Assign Reviewee List
    const toggleAssignRevieweeButton = document.getElementById("toggleAssignRevieweeList");
    const assignRevieweeList = document.getElementById("assignRevieweeList");
    if (toggleAssignRevieweeButton && assignRevieweeList) {
        const showRevieweeList = localStorage.getItem("toggleAssignRevieweeList");
        if (showRevieweeList === "true") {
            assignRevieweeList.style.display = "block";
            toggleAssignRevieweeButton.textContent = "Hide Reviewee List";
        } else {
            assignRevieweeList.style.display = "none";
            toggleAssignRevieweeButton.textContent = "Add Reviewee";
        }

        toggleVisibility(toggleAssignRevieweeButton, assignRevieweeList, "Add Reviewee", "Hide Reviewee List");
    }

    // Toggle Assign Reviewer List
    const toggleAssignReviewerButton = document.getElementById("toggleAssignReviewerList");
    const assignReviewerList = document.getElementById("assignReviewerList");
    if (toggleAssignReviewerButton && assignReviewerList) {
        const showReviewerList = localStorage.getItem("toggleAssignReviewerList");
        if (showReviewerList === "true") {
            assignReviewerList.style.display = "block";
            toggleAssignReviewerButton.textContent = "Hide Reviewer List";
        } else {
            assignReviewerList.style.display = "none";
            toggleAssignReviewerButton.textContent = "Add Reviewer";
        }

        toggleVisibility(toggleAssignReviewerButton, assignReviewerList, "Add Reviewer", "Hide Reviewer List");
    }

    const addCourseButton = document.getElementById("addCourseButton");
    const addCourseCard = document.getElementById("addCourseCard");
    if (addCourseButton && addCourseCard) {
        addCourseButton.addEventListener("click", function () {
            if (addCourseCard.classList.contains("d-none")) {
                addCourseCard.classList.remove("d-none");
                addCourseButton.textContent = "Hide Add Course Form";
            } else {
                addCourseCard.classList.add("d-none");
                addCourseButton.textContent = "Add Course";
            }
        });
    }

    // Update Word Count and Quality Indicator for the Review
    const reviewTextarea = document.getElementById("review");
    const reviewQualityIndicator = document.getElementById("reviewQualityIndicator");
    const wordCountIndicator = document.getElementById("wordCountIndicator");

    if (reviewTextarea && reviewQualityIndicator && wordCountIndicator) {
        reviewTextarea.addEventListener("input", function () {
            updateWordCountAndQuality();
        });
    }

    function updateWordCountAndQuality(textarea) {
        // Get the word count and quality indicator for the current textarea
        const wordCountIndicator = textarea.nextElementSibling; // Assuming <small> follows the <textarea>
        const qualityIndicator = textarea.previousElementSibling.querySelector('.quality-indicator');

        const wordCount = textarea.value.split(/\s+/).filter((word) => word.length > 0).length;

        // Update word count indicator
        wordCountIndicator.innerText = `Word Count: ${wordCount}`;

        // Update quality indicator
        if (wordCount < 5) {
            qualityIndicator.innerHTML = `<i class="bi bi-exclamation-triangle-fill text-warning"></i> - Too short, think of what when well`;
        } else if (wordCount < 10) {
            qualityIndicator.innerHTML = `<i class="bi bi-hand-thumbs-down-fill text-warning"></i> - Could be improved, surely there is more to say`;
        } else if (wordCount < 15) {
            qualityIndicator.innerHTML = `<i class="bi bi-check-circle-fill text-success"></i> - Good job, you're on the right track`;
        } else if (wordCount < 20) {
            qualityIndicator.innerHTML = `<i class="bi bi-star-fill text-warning"></i> - That is better, already showing potential as a mentor`;
        } else {
            qualityIndicator.innerHTML = `<i class="bi bi-trophy-fill text-warning"></i> - Great Work, you're a star in making reviews`;
        }
    }

    // Apply the word count and quality update to each textarea
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach((textarea) => {
        textarea.addEventListener('input', function () {
            updateWordCountAndQuality(textarea);
        });
    });

    // Concatenate all inputs into the 'review' field on submit
    const reviewForm = document.getElementById("reviewForm");
    if (reviewForm) {
        reviewForm.addEventListener("submit", function () {
            const mainReview = document.getElementById("review").value.trim();
            const positiveFeedback = document.getElementById("positive-feedback").value.trim();
            const improvementFeedback = document.getElementById("improvement-feedback").value.trim();

            // Concatenate all inputs into one review text
            const concatenatedReview = `${mainReview}\n\nWhat did the student do well?\n${positiveFeedback}\n\nWhat could be improved?\n${improvementFeedback}`;

            // Set the concatenated review back to the hidden review textarea
            document.getElementById("review").value = concatenatedReview;
        });
    }
});
