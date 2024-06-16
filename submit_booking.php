<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $destination = $_POST['destination'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tour_travel_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO bookings (name, email, phone, checkin, checkout, destination)
    VALUES ('$name', '$email', '$phone', '$checkin', '$checkout', '$destination')";

    $bookingSuccessful = false;

    if ($conn->query($sql) === TRUE) {
        $bookingSuccessful = true;
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .back-button {
            background-color: orange;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        .back-button:hover {
            background-color: darkorange;
        }
    </style>
    <script>
        function redirectToPackages() {
            setTimeout(function() {
                window.location.href = "index.html";
            }, 2000); // Redirects after 2 seconds
        }
    </script>
</head>
<body>
    <div class="container">
        <?php if (isset($bookingSuccessful) && $bookingSuccessful === true) : ?>
            <h1>Booking successful!</h1>
            <script>redirectToPackages();</script>
        <?php else : ?>
            <h1>Error</h1>
            <p><?php echo isset($errorMessage) ? $errorMessage : 'Unknown error'; ?></p>
            <a href="packages.html" class="back-button">Go back to packages</a>
        <?php endif; ?>
    </div>
</body>
</html>
