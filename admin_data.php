<?php
session_start();

// Include the database configuration file
require_once 'database.php';

// Check if the admin is already logged in, redirect to the data page
if (isset($_SESSION['admin_username'])) {
    header("Location: admin_data_view.php");
    exit;
}

// Handle login form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve admin details from the database
    $sql = "SELECT * FROM  admin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['admin_username'] = $username;
        header("Location: admin_data_view.php");
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
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            background-color: #007bff;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 3px;
        }
        .container form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .container .error-message {
            color: #ff0000;
            margin-bottom: 10px;
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
    </style>
</head>
<body>
    <div class="container" >
    
        <h2>Admin Login</h2>
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
            <a href="index.php">User Login</a> 
        </div>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
