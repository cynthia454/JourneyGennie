<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
include('database.php'); // Include your database connection file

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password1 =$_POST['password'];
    $verification = 0;
    $otp = rand(100000, 999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Save user details in the database
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, otp, verification) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $fullname, $email, $password1, $otp,$verification);

    if ($stmt->execute()) {
        // Send OTP via email or phone based on user selection
         $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 0;
                $mail->isSMTP();                                            
                $mail->Host       = 'smtp.office365.com';                      
                $mail->SMTPAuth   = true;                                  
                $mail->Username   = 'tourtravel123@outlook.com'; // Update with your SMTP username          
                $mail->Password   = 'tourtravel456'; // Update with your SMTP password                  
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        
                $mail->Port       = 587;                                  

                //Recipients
                $mail->setFrom('tourtravel123@outlook.com', 'Tour Travel'); // Update with your details
                $mail->addAddress($email);    

                // Content
                $mail->isHTML(true);                                  
                $mail->Subject = 'Your OTP for Registration';
                $mail->Body    = 'Hello '.$username.', your OTP for email verification is: ' . $otp;

                $mail->send();

                // Store OTP and user email in session
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;

                // Redirect to OTP verification page
                header('Location: otp-form.html');
                exit();
            } catch (Exception $e) {
                // Log error for debugging
                error_log("Error sending email: " . $e->getMessage(), 0);
                echo "Message could not be sent. Please try again later.";
            }

      
        // header("Location:otp-form.html");
      
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed.']);
    }

    $stmt->close();
    $conn->close();
}
?>
