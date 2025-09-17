const correctEmail = "synthesisbikaner@gmail.com";
const correctPassword = "synthesis@2024";

function validateLogin(event) {
    event.preventDefault(); // Prevent form reload

    const Email = document.getElementById("email").value;
    const Password = document.getElementById("password").value;

    if (Email === correctEmail && Password === correctPassword) {
        alert("Login successful! Redirecting...");
        window.location.href = "/emp"; // ✅ Laravel route
    } else {
        alert("Invalid Credentials");
    }
}
