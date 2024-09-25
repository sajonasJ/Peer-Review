// Function to determine the greeting based on the current time
function getGreeting() {
    const today = new Date();
    const hour = today.getHours();
    let greeting = "Good Evening"; // Default to evening

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

// Set the greeting and date on page load
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('greeting').innerHTML = getGreeting() + ", User!";
    document.getElementById('currentDate').innerHTML = "Today's Date: " + getCurrentDate();
});
