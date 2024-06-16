<?php
session_start();
include('database.php'); // Include your database connection file

if (isset($_POST['otp'])) {
    $otp = $_POST['otp'];
    $email = $_SESSION['email'];

    // Prepare the query to fetch the user with the given email and OTP
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND otp = ?");
    $stmt->bind_param("si", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is correct, update the verification status
        $stmt = $conn->prepare("UPDATE users SET verification = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo "<script>
                alert('OTP verified successfully. Your account is now activated.');
                window.location.href = 'login.html'; // Redirect to login page or another page
              </script>";
    } else {
        echo "<script>
                alert('Invalid OTP. Please try again.');
                window.history.back(); // Go back to the OTP form page
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
            alert('OTP not received. Please try again.');
            window.history.back(); // Go back to the OTP form page
          </script>";
}
?>
