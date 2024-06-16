<?php
session_start();
include('database.php');

// Check if email is set in the session
if(isset($_SESSION['email'])) {
    // Retrieve email from session
    $email = $_SESSION['email'];

    // Initialize variables to store user and profile data
    $user_data = [];
    $profile_data = [];

    // Retrieve user data
    $stmt_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt_user->bind_param("s", $email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if($result_user->num_rows > 0) {
        // Fetch user data
        $user_data = $result_user->fetch_assoc();
    }

    // Retrieve profile data
    $stmt_profile = $conn->prepare("SELECT * FROM profile WHERE user_id = (SELECT id FROM users WHERE email = ? LIMIT 1)");
    $stmt_profile->bind_param("s", $email);
    $stmt_profile->execute();
    $result_profile = $stmt_profile->get_result();

    if($result_profile->num_rows > 0) {
        // Fetch profile data
        $profile_data = $result_profile->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            background-image: url(7362.jpg);
            background-size: cover;
        }
        .profile-form {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin-right: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Ensure padding and border are included in the total width */
        }
        input[type="submit"] {
            background-color: orange;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%; /* Make submit button fit the container width */
            box-sizing: border-box; /* Ensure padding and border are included in the total width */
        }
        input[type="submit"]:hover {
            background-color: darkorange;
        }
        .logout-btn {
            background-color: red;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            box-sizing: border-box; /* Ensure padding and border are included in the total width */
        }
        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
     <div class="profile-form">
        <h1>Profile</h1>
        <form id="profile-form" action="submit-profile.php" method="POST">
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo isset($user_data['fullname']) ? $user_data['fullname'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male" <?php echo isset($profile_data['gender']) && $profile_data['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo isset($profile_data['gender']) && $profile_data['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo isset($profile_data['gender']) && $profile_data['gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo isset($profile_data['address']) ? $profile_data['address'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" rows="4" required><?php echo isset($profile_data['bio']) ? $profile_data['bio'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Profile">
            </div>
        </form>
        <form id="logout-form" action="logout.php" method="POST">
            <input type="submit" value="Logout" class="logout-btn">
        </form>
    </div>
</body>
</html>
