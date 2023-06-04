<?php
session_start();

// Include the database configuration file
require_once 'database.php';

// Check if the user is already logged in, redirect to the contact page
if (isset($_SESSION['username'])) {
    header("Location: contact.php");
    exit;
}

// Handle login form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user details from the database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username;
        header("Location: contact.php");
        exit;
    } else {
        // Invalid credentials
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">  
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-image: url("./image/adminbanner.jpg");
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
            width: 120px; /* Adjust the width as needed */
            height: 120px; /* Adjust the height as needed */
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

        .container p.error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        @media (max-width: 576px) {
            .container {
                max-width: 300px;
                margin-top: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="container" style="padding-top: 3px;">
        
        <img class="logo" src="./image/marklogo.jpg" alt="Logo" id="logoImage"> <!-- Add your logo image here -->
        <h2>Employee Login</h2>
        <?php if (isset($error)) { ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <input type="submit" name="submit" value="Login">
            </div>
            
            <hr/>
            <div style="text-align: right;">
            <a href="admin_data.php">Admin Login</a> | <a onclick="confirmAccountCreation()" href="create-account.php">Create Account</a>
        </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var logoImage = document.getElementById('logoImage');
            var imageWidth = logoImage.naturalWidth;
            var imageHeight = logoImage.naturalHeight;
            console.log('Logo Dimensions: ' + imageWidth + 'px x ' + imageHeight + 'px');
        });
    </script>
    <script>
    function confirmAccountCreation() {
        // Generate a random calculation
        var num1 = Math.floor(Math.random() * 10) + 1;
        var num2 = Math.floor(Math.random() * 10) + 1;
        var sum = num1 + num2;

        // Prompt the user with the calculation
        var userAnswer = prompt("To create an account, please solve the following calculation: " + num1 + " + " + num2 + " = ?");

        // Validate the user's answer
        if (userAnswer && parseInt(userAnswer) === sum) {
            // Redirect to create-account.php
            window.location.href = "create-account.php";
        } else {
            // Show an alert for incorrect answer
            alert("Incorrect answer. Please try again.");
            
            // Redirect to index.php
            setTimeout(function() {
                window.location.href = "index.php";
            }, 0);
        }
    }
    </script>


    
</body>
</html>
