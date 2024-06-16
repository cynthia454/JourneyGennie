<?php

session_start();

    if (isset($_POST["submit"])) {
        // reCAPTCHA was successfully verified
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Connect to the database
        $servername = "localhost";
        $username = "root"; // Replace with your database username
        $dbpassword = ""; // Replace with your database password
        $dbname = "tour_travel_db";

        $conn = new mysqli($servername, $username, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        if (!$stmt) {
            die("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Bind result variables
            $stmt->bind_result($stored_password);
            $stmt->fetch();

            // Verify the password without hashing
            if ($password === $stored_password) {
                echo "<script>
                        alert('Login successful!');
                        window.location.href = 'index.html';
                      </script>";
                      $_SESSION['email']=$email;
                // Perform actions after successful login (e.g., redirect to a dashboard)
            } else {
                echo "<script>
                        alert('Invalid password.');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('No user found with this email address.');
                    window.history.back();
                  </script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>
                alert('reCAPTCHA verification failed. Please try again.');
                window.history.back();
              </script>";
    }
?>
