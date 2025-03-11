<?php
session_start();
include('config.php');
// include('session.php');

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo '<p class="error">Passwords do not match!</p>';
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $query = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<p class="error">The email address is already registered!</p>';
        } else {
            // Insert new user
            $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $username, $password_hash, $email);
            $result = $stmt->execute();

            if ($result) {
                echo '<p class="success">Your registration was successful!</p>';
            } else {
                echo '<p class="error">Your registration was not successful!</p>';
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="register.css">
    <script>
    function validateEmail() {
        const email = document.getElementById("email").value;
        const emailError = document.getElementById("email-error");
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {
            emailError.textContent = "Please enter a valid email address.";
            return false;
        } else {
            emailError.textContent = "";
            return true;
        }
    }

    function validatePasswords() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        const passwordError = document.getElementById("password-error");

        if (password !== confirmPassword) {
            passwordError.textContent = "Passwords do not match.";
            return false;
        } else {
            passwordError.textContent = "";
            return true;
        }
    }

    function validateForm(event) {
        const emailValid = validateEmail();
        const passwordsValid = validatePasswords();

        if (!emailValid || !passwordsValid) {
            event.preventDefault(); // Prevent form submission if email or passwords are invalid
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form[name='signup-form']");
        form.addEventListener("submit", validateForm);
    });
    </script>
</head>
<body>
<form method="post" action="" name="signup-form">

    <div class="form-element">
        <label>Username</label>
        <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
    </div>

    <div class="form-element">
        <label>Email</label>
        <input type="email" name="email" id="email" required />
    </div>
    <div id="email-error" style="color: red;"></div>

    <div class="form-element">
        <label>Password</label>
        <input type="password" name="password" id="password" required />
    </div>

    <div class="form-element">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm-password" required />
    </div>
    <div id="password-error" style="color: red;"></div>

    <button type="submit" name="register" value="register">Register</button>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</form>
</body>
</html>
