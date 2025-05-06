<?php
$servername = "your_server_name";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Authentication/Connection to DB server failed
}
echo "Connected successfully to the database server and selected the database."; // Connection successful

// You would then proceed to check user credentials against the database
// For example, getting txtEmail and txtPass from the form POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['txtEmail'];
    $pass = $_POST['txtPass']; // Remember to hash passwords in real applications!
}
$conn->close();
?>