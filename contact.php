<!DOCTYPE html>
<html>
<head>
    <title>Contact Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .hidden {
            display: none;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <?php
        session_start();

        // Include the database configuration file
        require_once 'database.php';

        // Check if the user is logged in, otherwise redirect to the login page
        if (!isset($_SESSION['username'])) {
            header("Location: index.php");
            exit;
        }

        // Handle contact form submission
        if (isset($_POST['submit'])) {
            $employeeName = $_POST['employeeName'];
            $teamLeader = $_POST['teamLeader'];
            $callsDialed = $_POST['callsDialed'];
            $SiteVisitScheduled = $_POST['SiteVisitScheduled'];
            $SiteVisitDone = $_POST['SiteVisitDone'];
            $bookingsDone = $_POST['bookingsDone'];

            // Insert contact details into the database
            $sql = "INSERT INTO contacts (employeeName, teamLeader, callsDialed, SiteVisitScheduled, SiteVisitDone, bookingsDone) VALUES ('$employeeName', '$teamLeader', '$callsDialed', '$SiteVisitScheduled','$SiteVisitDone', '$bookingsDone')";
            $conn->query($sql);

            // Redirect to the contact page
            header("Location: thankyou.php");
            exit;
        }

        // Retrieve user details from the database
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        $employeeName = $user['employeeName'];
        $teamLeader = $user['teamLeader'];
        ?>

        <h2 class="mt-5">User Details</h2>
        <p class="lead">Welcome, <?php echo $employeeName; ?></p>
        <p class="lead">Team leader: <?php echo $teamLeader; ?></p>

        <h3 class="mt-4">Update Details</h3>
        <form method="POST" action="">
            <div class="form-group hidden">
                <label for="employeeName">Employee Name:</label>
                <input type="text" class="form-control" id="employeeName" name="employeeName" value="<?php echo $employeeName; ?>" readonly>
            </div>
            <div class="form-group hidden">
                <label for="teamLeader">Team leader:</label>
                <input type="text" class="form-control" id="teamLeader" name="teamLeader" value="<?php echo $teamLeader; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="callsDialed">Number of Calls Dialed:</label>
                <input type="number" class="form-control" id="callsDialed" name="callsDialed" required>
            </div>
            <div class="form-group">
                <label for="SiteVisitScheduled">Site Visit Scheduled:</label>
                <input type="number" class="form-control" id="SiteVisitScheduled" name="SiteVisitScheduled" required>
            </div>
            <div class="form-group">
                <label for="SiteVisitDone">Site Visit Done:</label>
                <input type="number" class="form-control" id="SiteVisitDone" name="SiteVisitDone" required>
            </div>
            <div class="form-group">
                <label for="bookingsDone">Number of Bookings Done:</label>
                <input type="number" class="form-control" id="bookingsDone" name="bookingsDone" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
            </div>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
