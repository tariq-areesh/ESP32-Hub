// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426

// Basic client-side validation for the feedback form
document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("feedback-form");
    if (!form) return;

    // Form submission event
    form.addEventListener("submit", function (e) {
        var name = form.elements["name"].value.trim();
        var email = form.elements["email"].value.trim();
        var projectTitle = form.elements["project_title"].value.trim();
        var experience = form.elements["experience_level"].value.trim();
        var feedbackType = form.querySelector("input[name='feedback_type']:checked");
        var improvements = form.querySelectorAll("input[name='improvements[]']:checked");
        var source = form.elements["source"].value;
        var message = form.elements["message"].value.trim();

        var errorMessages = [];

        if (name === "") errorMessages.push("Name is required.");
        if (email === "") {
            errorMessages.push("Email is required.");
        } else {
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                errorMessages.push("Please enter a valid email address.");
            }
        }
        if (projectTitle === "") errorMessages.push("Project title is required.");
        if (experience === "") errorMessages.push("Please enter your ESP32 experience level.");
        if (!feedbackType) errorMessages.push("Please select the feedback type.");
        if (improvements.length === 0) errorMessages.push("Please select at least one improvement option.");
        if (!source) errorMessages.push("Please select how you heard about ESP32 Hub.");
        if (message === "") errorMessages.push("Feedback message cannot be empty.");

        var errorBox = document.getElementById("feedback-errors");
        if (errorMessages.length > 0) {
            e.preventDefault();
            if (errorBox) {
                errorBox.innerHTML = "";
                errorMessages.forEach(function (msg) {
                    var p = document.createElement("p");
                    p.textContent = msg;
                    errorBox.appendChild(p);
                });
                errorBox.style.display = "block";
            } else {
                alert(errorMessages.join("\n"));
            }
        }
    });
});
