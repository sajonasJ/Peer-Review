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
                    instance.input.setAttribute('placeholder', 'Select Time..');
                }
            },
            onChange: function (selectedDates, dateStr, instance) {
                instance.input.removeAttribute('placeholder');
            }
        });
    }

    // Tab Navigation
    const tabs = document.querySelectorAll('.nav-tab');
    const contents = document.querySelectorAll('.tab-content');

    if (tabs && contents) {
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
    }

    // Toasts for Success/Error Messages
    const successToastEl = document.getElementById('successToast');
    const errorToastEl = document.getElementById('errorToast');

    if (successToastEl) {
        new bootstrap.Toast(successToastEl).show();
    }
    if (errorToastEl) {
        new bootstrap.Toast(errorToastEl).show();
    }

    // Student Search Filtering (only if elements are present)
    const addStudentBtn = document.getElementById('addStudentBtn');
    const backToEnrolledBtn = document.getElementById('backToEnrolledBtn');
    const enrolledStudentsCard = document.getElementById('enrolledStudentsCard');
    const enrollStudentCard = document.getElementById('enrollStudentCard');
    const searchInput = document.getElementById('studentSearch');
    const studentList = document.getElementById('studentList');

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

    if (studentList && searchInput) {
        const students = Array.from(studentList.querySelectorAll('.list-group-item'));

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

document.getElementById('courseFile').addEventListener('change', function (event) {
    const fileList = event.target.files;
    const fileTableBody = document.getElementById('fileTableBody');
    fileTableBody.innerHTML = ''; // Clear previous entries

    Array.from(fileList).forEach(file => {
        const row = document.createElement('tr');
        
        // Create cells for the file name, size, and status
        const nameCell = document.createElement('td');
        nameCell.textContent = file.name;

        const sizeCell = document.createElement('td');
        sizeCell.textContent = (file.size / 1024).toFixed(2) + ' KB'; // Convert bytes to KB

        const statusCell = document.createElement('td');
        statusCell.textContent = 'Pending'; // Initial status

        // Append cells to the row
        row.appendChild(nameCell);
        row.appendChild(sizeCell);
        row.appendChild(statusCell);
        
        // Append the row to the table body
        fileTableBody.appendChild(row);
    });
});

document.getElementById('saveFile').addEventListener('click', function () {
    const fileInput = document.getElementById('courseFile');
    if (fileInput.files.length === 0) {
        alert('Please select a file before saving.');
        return;
    }

    // Here you can implement any logic to save the file details, such as storing in local storage or displaying a message
    alert('File information saved!'); // Placeholder for actual saving logic
    
    // Update the status of the uploaded file to "Saved"
    const fileTableBody = document.getElementById('fileTableBody');
    const rows = fileTableBody.getElementsByTagName('tr');
    if (rows.length > 0) {
        // Assuming you want to update the status of the first uploaded file
        const statusCell = rows[0].getElementsByTagName('td')[2]; // Access the status cell
        statusCell.textContent = 'Saved'; // Update the status to "Saved"
    }
});
