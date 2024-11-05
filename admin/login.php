<?php
// Check if the form is submitted
session_start();
include_once 'mydb.class.php';
$auth = new UserAuthentication;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Replace 'admin' and 'password' with your desired credentials
    $valid_username = "admin";
    $valid_password = "password";

    // Check if the credentials are valid
    if ($auth->verifyUser($username, $password)) {
        // Redirect to the desired page, e.g., index.php
        $_SESSION['logged_in'] = true;
        header("Location: entire-list.php");
        exit();
    } else {
        // Display an error message
        $message = "Invalid username or password.";
        $_SESSION['message'] = $message;
        header("Location: index.php");
        exit();
    }
}
?>