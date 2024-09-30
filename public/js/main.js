document.addEventListener('DOMContentLoaded', function () {
    // Update Greeting and Date
    const greetingElement = document.getElementById('greeting');
    const dateElement = document.getElementById('currentDate');

    if (greetingElement && dateElement) {
        const userName = greetingElement.textContent.trim() || 'User';

        function getGreeting() {
            const today = new Date();
            const hour = today.getHours();
            return hour >= 5 && hour < 12 ? "Good Morning" : hour >= 12 && hour < 17 ? "Good Afternoon" : "Good Evening";
        }

        function getCurrentDate() {
            const today = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return today.toLocaleDateString('en-US', options);
        }

        greetingElement.innerHTML = `${getGreeting()}, ${userName}`;
        dateElement.innerHTML = `Today's Date: ${getCurrentDate()}`;
    }

    // Initialize Flatpickr
    flatpickr("#due_date_input", {
        inline: true,
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        minDate: "today",
    });

    flatpickr("#due_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false,
        minuteIncrement: 15,
        onReady: function(selectedDates, dateStr, instance) {
            if (!dateStr) {
                instance.input.setAttribute('placeholder', 'Select Time..');
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            instance.input.removeAttribute('placeholder');
        }
    });

    // Tab Navigation
    const tabs = document.querySelectorAll('.nav-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (event) {
            if (!tab.getAttribute('data-target')) {
                return;
            }
            event.preventDefault();
            tabs.forEach(t => t.classList.remove('active-tab'));
            contents.forEach(content => content.classList.remove('active-content'));
            tab.classList.add('active-tab');
            document.getElementById(tab.getAttribute('data-target')).classList.add('active-content');
        });
    });

    // Toasts for Success/Error Messages
    const successToastEl = document.getElementById('successToast');
    const errorToastEl = document.getElementById('errorToast');

    if (successToastEl) {
        new bootstrap.Toast(successToastEl).show();
    }
    if (errorToastEl) {
        new bootstrap.Toast(errorToastEl).show();
    }

    // Student Search Filtering
    const addStudentBtn = document.getElementById('addStudentBtn');
    const backToEnrolledBtn = document.getElementById('backToEnrolledBtn');
    const enrolledStudentsCard = document.getElementById('enrolledStudentsCard');
    const enrollStudentCard = document.getElementById('enrollStudentCard');
    const searchInput = document.getElementById('studentSearch');
    const studentList = document.getElementById('studentList');
    const students = Array.from(studentList.querySelectorAll('.list-group-item'));

    if (addStudentBtn && backToEnrolledBtn && enrolledStudentsCard && enrollStudentCard) {
        // Show "Enroll Existing Student" card when "Add Student" button is clicked
        addStudentBtn.addEventListener('click', function () {
            enrolledStudentsCard.style.display = 'none';
            enrollStudentCard.style.display = 'block';
        });

        // Show "Enrolled Students" card when "Back" button is clicked
        backToEnrolledBtn.addEventListener('click', function () {
            enrollStudentCard.style.display = 'none';
            enrolledStudentsCard.style.display = 'block';
        });
    }

    if (searchInput && studentList) {
        // Filter students when typing in the search bar
        searchInput.addEventListener('input', function () {
            const filter = searchInput.value.toLowerCase();

            students.forEach(function (student) {
                const studentInfo = student.querySelector('.student-info').textContent.toLowerCase();

                // Check if student info contains the filter value and adjust display accordingly
                if (studentInfo.includes(filter)) {
                    student.classList.remove('hidden');
                } else {
                    student.classList.add('hidden');
                }
            });
        });
    }
});
