<?php
session_start(); // Start the session at the very beginning

$servername = "plesk.remote.ac";
$database = "WS371632_IoT";
$username = "WS371632_IoT";
$password = "!v65Rny56"; // Ensure this is kept secure and not hardcoded in production if possible
$dbname = "WS371632_IoT"; // Database name was in $database, but mysqli constructor expects it as 4th param

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log error to a file instead of exposing to user in production
    // error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later."); // User-friendly message
}

// Initialize variables to avoid undefined notices
$auth_error = "";
$login_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['txtEmail']) && isset($_POST['txtPass'])) {
        $email = $_POST['txtEmail'];
        $pass_submitted = $_POST['txtPass']; // User submitted password

        // Prepare and bind
        // Assuming your table is named 'User' and columns are 'email' and 'password'
        // Adjust table and column names if they are different.
        $stmt = $conn->prepare("SELECT id, email, password FROM User WHERE email = ?");
        if ($stmt === false) {
            // Log error
            // error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            $auth_error = "An error occurred during login. Please try again later.";
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $stored_password_hash = $user['password']; // This should be a hashed password

                // --- IMPORTANT: Password Verification ---
                // In a real application, you MUST use password_verify()
                // For example: if (password_verify($pass_submitted, $stored_password_hash)) {
                // For this example, we'll do a direct comparison,
                // but THIS IS INSECURE for plain text passwords.
                // Replace this with proper password hashing and verification.

                // **DEVELOPMENT ONLY - PLAIN TEXT PASSWORD COMPARISON (INSECURE)**
                // if ($pass_submitted === $stored_password_hash) {

                // **PRODUCTION READY - HASHED PASSWORD VERIFICATION (SECURE)**
                if (password_verify($pass_submitted, $stored_password_hash)) {
                    // Password is correct
                    $login_success = true;
                    $_SESSION['user_id'] = $user['id']; // Store user ID in session
                    $_SESSION['user_email'] = $user['email']; // Store email in session
                    $_SESSION['loggedin'] = true;

                    // Redirect to a logged-in user page (e.g., dashboard.php)
                    header("Location: home.php"); // Create dashboard.php for logged-in users
                    exit();
                } else {
                    // Invalid password
                    $auth_error = "Invalid email or password.";
                }
            } else {
                // No user found with that email
                $auth_error = "Invalid email or password.";
            }
            $stmt->close();
        }
    } else {
        $auth_error = "Email and password are required.";
    }
}

$conn->close();

// If login was not successful and there's an error, redirect back to login with an error message
// Or display the error on this page (less ideal for user experience)
if (!$login_success && !empty($auth_error)) {
    // You can pass the error message back to the login page via query parameter or session
    $_SESSION['login_error'] = $auth_error;
    header("Location: login.html?error=" . urlencode($auth_error)); // Assuming your login form is login.html
    exit();
}

// Fallback if something unexpected happens, though the above redirects should handle most cases.
if (!$login_success) {
    echo "Authentication failed. <a href='login.html'>Try again</a>"; // Adjust 'login.html' if your form page has a different name
}

?>