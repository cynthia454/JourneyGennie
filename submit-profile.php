<?php
// Include the database connection file
session_start();
include('database.php');

// Retrieve email from session
$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $bio = $_POST['bio'];

    // Update user's information in the users table
    $stmt_user = $conn->prepare("UPDATE users SET fullname=? WHERE email=?");
    if (!$stmt_user) {
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt_user->bind_param("ss", $fullname, $email);
    if (!$stmt_user->execute()) {
        die("Error updating user's information: " . $stmt_user->error);
    }
    $stmt_user->close();

    // Fetch the user ID from the users table using the email
    $stmt_user_id = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt_user_id) {
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt_user_id->bind_param("s", $email);
    if (!$stmt_user_id->execute()) {
        die("Error fetching user ID: " . $stmt_user_id->error);
    }
    $result_user_id = $stmt_user_id->get_result();
    if (!$result_user_id) {
        die("Error getting result: " . $conn->error);
    }
    $user_row = $result_user_id->fetch_assoc();
    $user_id = $user_row['id'];
    $stmt_user_id->close();

    // Check if a profile record exists for the user
    $stmt_profile_check = $conn->prepare("SELECT profile_id FROM profile WHERE user_id = ?");
    if (!$stmt_profile_check) {
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt_profile_check->bind_param("i", $user_id);
    if (!$stmt_profile_check->execute()) {
        die("Error executing statement: " . $stmt_profile_check->error);
    }
    $result_profile_check = $stmt_profile_check->get_result();
    if (!$result_profile_check) {
        die("Error getting result: " . $conn->error);
    }

    if ($result_profile_check->num_rows > 0) {
        // Update existing profile record
        $stmt_profile = $conn->prepare("UPDATE profile SET gender=?, address=?, bio=? WHERE user_id=?");
        if (!$stmt_profile) {
            die("Prepare statement failed: " . $conn->error);
        }
        $stmt_profile->bind_param("sssi", $gender, $address, $bio, $user_id);
    } else {
        // Insert new profile record
        $stmt_profile = $conn->prepare("INSERT INTO profile (user_id, gender, address, bio) VALUES (?, ?, ?, ?)");
        if (!$stmt_profile) {
            die("Prepare statement failed: " . $conn->error);
        }
        $stmt_profile->bind_param("isss", $user_id, $gender, $address, $bio);
    }

    if (!$stmt_profile->execute()) {
        die("Error updating profile: " . $stmt_profile->error);
    }

    // Close prepared statement and database connection
    $stmt_profile->close();
    $conn->close();

    // Redirect to index page after successful update
    header("Location: index.html");
    exit();
}
?>
