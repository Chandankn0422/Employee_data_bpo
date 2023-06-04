<?php
// Check if the admin is not logged in, redirect to the login page
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_data.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>User Data</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }

        h2 {
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            margin-bottom: 30px;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 6px 12px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .btn-primary,
        .btn-danger {
            margin-right: 5px;
        }

        .actions {
            white-space: nowrap;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            color: #fff;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 16px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: #333;
        }

        .content {
            margin-top: 80px;
            padding: 20px;
        }

        @media only screen and (max-width: 768px) {
            .navbar a {
                float: none;
                display: block;
                text-align: left;
            }
        }
        
        /* Mobile Navigation Styles */
        .mobile-navbar {
            display: none;
        }

        @media only screen and (max-width: 768px) {
            .navbar {
                display: none;
            }

            .mobile-navbar {
                display: block;
                background-color: #333;
                overflow: hidden;
            }

            .mobile-navbar a {
                float: left;
                color: #fff;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 16px;
            }

            .mobile-navbar a:hover {
                background-color: #ddd;
                color: #333;
            }
        }

        /* Desktop Navigation Styles */
        .desktop-navbar {
            display: none;
        }

        @media only screen and (min-width: 769px) {
            .navbar {
                display: none;
            }

            .desktop-navbar {
                display: block;
                background-color: #333;
                overflow: hidden;
            }

            .desktop-navbar a {
                float: left;
                color: #fff;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 16px;
            }

            .desktop-navbar a:hover {
                background-color: #ddd;
                color: #333;
            }
        }
    </style>
</head>
<body>
<div class="mobile-navbar" style="
    height: 66px;
    width: 632px">
    <a class="nav-link" href="admin_data_view.php">Team View</a>
    <a class="nav-link" href="employee_view.php">Employee View</a>
    <a class="nav-link" href="view_users.php">Users Update</a>
</div>

<div class="desktop-navbar" >
    <a class="nav-link" href="admin_data_view.php">Team View</a>
    <a class="nav-link" href="employee_view.php">Employee View</a>
    <a class="nav-link" href="view_users.php">Users Update</a>
</div>

    <div class="container">
        <h2>User Data</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Team Leader</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database configuration
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "marks_user";

                // Create a database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve user data from the database 
                $sql = "SELECT * FROM users"; 
                $result = $conn->query($sql);

                // Handle edit form submission
                if (isset($_POST['edit'])) {
                    $id = $_POST['id'];
                    $employeeName = $_POST['employeeName'];
                    $teamLeader = $_POST['teamLeader'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    // Update the user record in the database
                    $updateSql = "UPDATE users SET employeeName = '$employeeName', teamLeader = '$teamLeader', username = '$username', password = '$password' WHERE id = '$id'";
                    $conn->query($updateSql);
                }

                // Handle delete action
                if (isset($_POST['delete'])) {
                    $id = $_POST['id'];

                    // Delete the user record from the database
                    $deleteSql = "DELETE FROM users WHERE id = '$id'";
                    $conn->query($deleteSql);
                }

                // Display user data
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <form method="POST" action="">
                                <td><input type="text" name="employeeName" value="<?php echo $row['employeeName']; ?>"></td>
                                <td><input type="text" name="teamLeader" value="<?php echo $row['teamLeader']; ?>"></td>
                                <td><input type="text" name="username" value="<?php echo $row['username']; ?>"></td>
                                <td><input type="password" name="password" value="<?php echo $row['password']; ?>"></td>
                                <td class="actions">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Delete User</button>
                                </td>
                            </form>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
        <br>
        <form method="POST" action="logout.php">
            <input type="submit" value="Logout" class="btn btn-danger">
        </form>
    </div>
    <script>
        $(document).ready(function() {
            // Hide specific links with the 'desktop-link' class in mobile view
            if ($(window).width() <= 768) {
                $('.desktop-link').hide();
            }
        });
    </script>

    <!-- Bootstrap JS -->
    
</body>
</html>
