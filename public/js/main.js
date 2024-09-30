document.addEventListener('DOMContentLoaded', function () {
    // Check if the greeting and currentDate elements exist
    const greetingElement = document.getElementById('greeting');
    const dateElement = document.getElementById('currentDate');

    // If greeting and date elements exist, update them
    if (greetingElement && dateElement) {
        // Get the current name from the server-rendered greeting
        const userName = greetingElement.textContent.trim() || 'User';

        // Function to determine the greeting based on the current time
        function getGreeting() {
            const today = new Date();
            const hour = today.getHours();
            let greeting = "Good Evening";

            if (hour >= 5 && hour < 12) {
                greeting = "Good Morning";
            } else if (hour >= 12 && hour < 17) {
                greeting = "Good Afternoon";
            }
            return greeting;
        }

        // Function to format and display the current date
        function getCurrentDate() {
            const today = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return today.toLocaleDateString('en-US', options);
        }

        // Set the greeting and date
        greetingElement.innerHTML = getGreeting() + ", " + userName + "";
        dateElement.innerHTML = "Today's Date: " + getCurrentDate();
    }

    // Initialize Flatpickr for the inline calendar and input field
    flatpickr("#due_date_input", {
        inline: true,          // Always show the calendar inline
        dateFormat: "Y-m-d",   // Date format for the input field
        altInput: true,        // Create an alternate input field
        altFormat: "d/m/Y",    // Format date as "DD/MM/YYYY"
        minDate: "today",      // Disable previous dates
    });

    // Initialize Flatpickr for the time picker with 12-hour format (AM/PM) and placeholder
    flatpickr("#due_time", {
        enableTime: true,        // Enable time selection
        noCalendar: true,        // Disable the calendar part
        dateFormat: "h:i K",     // Format time as "H:MM AM/PM"
        time_24hr: false,        // Use 12-hour format with AM/PM
        minuteIncrement: 15,     // Set 15-minute increments
        onReady: function(selectedDates, dateStr, instance) {
            // Set placeholder for time picker if no time is selected
            if (!dateStr) {
                instance.input.setAttribute('placeholder', 'Select Time..');
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            // Remove placeholder when a time is selected
            instance.input.removeAttribute('placeholder');
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.nav-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (event) {
            // Skip event.preventDefault() for the "Home" link
            if (!tab.getAttribute('data-target')) {
                return;
            }

            event.preventDefault();

            // Remove active classes from all tabs and content sections
            tabs.forEach(t => t.classList.remove('active-tab'));
            contents.forEach(content => content.classList.remove('active-content'));

            // Add active class to the clicked tab and corresponding content
            tab.classList.add('active-tab');
            const targetId = tab.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active-content');
        });
    });
});

