<?php
session_start();

// Include the database configuration file
require_once 'database.php';

// Check if the user is already logged in, redirect to the contact page
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Handle account creation form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $employeeName = $_POST['employeeName'];
    $teamLeader = $_POST['teamLeader'];

    // Check if the username already exists
    $checkSql = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Username already exists, display error message
        $error = "Username already exists!";
    } else {
        // Insert new user into the database
        $insertSql = "INSERT INTO users (username, password, employeeName, teamLeader) VALUES ('$username', '$password', '$employeeName', '$teamLeader')";
        $conn->query($insertSql);

        // Display success message and redirect to index.php
        echo '<script>alert("Account created successfully!");</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit;
    }
}
?>

<!-- Rest of the HTML code remains the same -->


<!-- Rest of the HTML code remains the same -->


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">  
    <title>Account Creation</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-image: url("./image/banner.jpg");
            background-size: cover;
            background-position: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            margin-top: 100px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container img.logo {
            display: block;
            margin: 0 auto;
            width: 150px; /* Adjust the width as needed */
            height: 150px; /* Adjust the height as needed */
            border-radius: 50%; /* Make the image circular */
            object-fit: cover; /* Ensure the image covers the entire circle */
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .container form div {
            margin-bottom: 15px;
        }

        .container form label {
            font-weight: bold;
        }

        .container form input[type="text"],
        .container form input[type="password"] {
            width: 100%;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        .container form input[type="submit"] {
            width: 100%;
            padding: 8px;
            background-color: #337ab7;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 3px;
        }

        .container form input[type="submit"]:hover {
            background-color: #286090;
        }

        .container p.error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="employeeName">Employee Name:</label>
                <input type="text" class="form-control" id="employeeName" name="employeeName" required>
            </div>
            <div class="form-group">
                <label for="teamLeader">Team Leader:</label>
                <input type="text" class="form-control" id="teamLeader" name="teamLeader" required>
            </div>
            <?php if (isset($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="submit" value="Create Account">
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
