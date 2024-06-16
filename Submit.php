<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Example of what you can do with the form data
    // For demonstration purposes, just echoing the data here
    echo "<h2>Form Submitted Successfully!</h2>";
    echo "<p>Name: $name</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Message: $message</p>";
} else {
    // Redirect back to the contact form if accessed directly
    header("Location: contact.html");
    exit;
}
?>
