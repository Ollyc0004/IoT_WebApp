<?php
session_start(); // Start the session at the very beginning

$servername = "plesk.remote.ac";
$database = "WS371632_IoT";
$username_db = "WS371632_IoT"; // Renamed to avoid conflict with form username
$password_db = "!v65Rny56";
$dbname = "WS371632_IoT";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    // error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

$auth_error = "";
$login_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if txtUsername and txtPass are set
    if (isset($_POST['txtUsername']) && isset($_POST['txtPass'])) {
        $form_username = $_POST['txtUsername']; // Changed from txtEmail
        $pass_submitted = $_POST['txtPass'];

        // Prepare and bind
        // Assuming your table is 'User' and username column is 'username'
        // and password column is 'password'. Adjust if different.
        $stmt = $conn->prepare("SELECT id, Username, password FROM User WHERE Username = ?"); // Query by username
        if ($stmt === false) {
            // error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            $auth_error = "An error occurred during login. Please try again later.";
        } else {
            $stmt->bind_param("s", $form_username); // Bind the username from the form
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $stored_password_hash = $user['password'];

                if (password_verify($pass_submitted, $stored_password_hash)) {
                    $login_success = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_username'] = $user['username']; // Store username in session
                    // You might also want to store the email if you fetched it
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['loggedin'] = true;

                    header("Location: home.php");
                    exit();
                } else {
                    $auth_error = "Invalid username or password.";
                }
            } else {
                $auth_error = "Invalid username or password.";
            }
            $stmt->close();
        }
    } else {
        $auth_error = "Username and password are required.";
        // It's possible only one is missing, refine this message if needed
        if (!isset($_POST['txtUsername'])) {
            $auth_error = "Username is required.";
        } elseif (!isset($_POST['txtPass'])) {
            $auth_error = "Password is required.";
        }
    }
}

$conn->close();

if (!$login_success && !empty($auth_error)) {
    $_SESSION['login_error'] = $auth_error;
    // Ensure your login form page can display this session error
    // For example, if your login form is login.php (or login.html parsed as php)
    header("Location: index.php?error=" . urlencode($auth_error)); // Or whatever your login form page is
    exit();
}

if (!$login_success) {
    echo "Authentication failed. <a href='index.php'>Try again</a>"; // Adjust login page name
}

?>