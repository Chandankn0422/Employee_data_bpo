<!DOCTYPE html>
<html>
<head>
    <title>Admin Data View</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">  
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Navigation bar styles */
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

        /* Content area style */
        .content {
            margin-top: 80px;
            padding: 20px;
        }
         /* Mobile styles */
        @media only screen and (max-width: 768px) {
            .navbar a {
                float: none;
                display: block;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <a href="admin_data_view.php">Team View </a>
        <a href="employee_view.php">Employee View</a>
        <a href="view_users.php">Users Update</a>
    </div>

    <!-- Content area -->
    <div class="content">
        <?php
        session_start();

        // Check if the admin is not logged in, redirect to the login page
        if (!isset($_SESSION['admin_username'])) {
            header("Location: admin_data.php");
            exit;
        }

        // Include the database configuration file
        require_once 'database.php';

        // Retrieve team data from the database
        $sql = "SELECT * FROM contacts";
        $result = $conn->query($sql);

        // Filter variables
        $fromDate = $_POST['fromDate'] ?? '';
        $toDate = $_POST['toDate'] ?? '';
        $teamLeader = $_POST['teamLeader'] ?? '';

        // Apply filters if provided
        $filterQuery = '';
        if (!empty($fromDate)) {
            $filterQuery .= " AND dateandTime >= '$fromDate 00:00:00'";
        }
        if (!empty($toDate)) {
            $toDate = date('Y-m-d', strtotime($toDate . '+1 day')); // Add 1 day to include the entire day
            $filterQuery .= " AND dateandTime < '$toDate 00:00:00'";
        }
        if (!empty($teamLeader)) {
            $filterQuery .= " AND teamLeader = '$teamLeader'";
        }

        // Retrieve filtered team data from the database
        $filterSql = "SELECT * FROM contacts WHERE 1 $filterQuery";
        $filterResult = $conn->query($filterSql);
        ?>

        <h2>Team View</h2>
        <form method="POST" action="" class="row">
            <div class="col-md-3">
                <label for="fromDate">From Date:</label>
                <input type="date" id="fromDate" name="fromDate" class="form-control" value="<?php echo $fromDate; ?>">
            </div>
            <div class="col-md-3">
                <label for="toDate">To Date:</label>
                <input type="date" id="toDate" name="toDate" class="form-control" value="<?php echo $toDate; ?>">
            </div>
            <div class="col-md-3">
                <label for="teamLeader">Team Leader:</label>
                <select id="teamLeader" name="teamLeader" class="form-control">
                    <option value="">All</option>
                    <!-- Add team leader options dynamically from the database -->
                    <?php
                    $teamLeaderSql = "SELECT DISTINCT teamLeader FROM contacts";
                    $teamLeaderResult = $conn->query($teamLeaderSql);
                    while ($row = $teamLeaderResult->fetch_assoc()) {
                        $selected = ($teamLeader == $row['teamLeader']) ? 'selected' : '';
                        echo "<option value='{$row['teamLeader']}' $selected>{$row['teamLeader']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 mt-4">
                <button type="submit" class="btn btn-primary">Apply Filter</button>
                <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset Filter</button>
            </div>
        </form>
        <br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Team Leader</th>
                    <th>Calls Dialed</th>
                    <th>Site Visits Scheduled</th>
                    <th>Site Visits Done</th>
                    <th>Bookings Done</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $teamLeaderTotals = array(); // Array to store team leader totals
                while ($row = $filterResult->fetch_assoc()) {
                    $teamLeader = $row['teamLeader'];
                    // Initialize the team leader totals if it doesn't exist
                    if (!isset($teamLeaderTotals[$teamLeader])) {
                        $teamLeaderTotals[$teamLeader] = array(
                            'callsDialed' => 0,
                            'SiteVisitScheduled' => 0,
                            'SiteVisitDone' => 0,
                            'bookingsDone' => 0
                        );
                    }
                    // Increment the totals for each field
                    $teamLeaderTotals[$teamLeader]['callsDialed'] += $row['callsDialed'];
                    $teamLeaderTotals[$teamLeader]['SiteVisitScheduled'] += $row['SiteVisitScheduled'];
                    $teamLeaderTotals[$teamLeader]['SiteVisitDone'] += $row['SiteVisitDone'];
                    $teamLeaderTotals[$teamLeader]['bookingsDone'] += $row['bookingsDone'];
                }

                // Display the team leader rows
                foreach ($teamLeaderTotals as $teamLeader => $totals) {
                ?>
                <tr>
                    <td><?php echo $teamLeader; ?></td>
                    <td><?php echo $totals['callsDialed']; ?></td>
                    <td><?php echo $totals['SiteVisitScheduled']; ?></td>
                    <td><?php echo $totals['SiteVisitDone']; ?></td>
                    <td><?php echo $totals['bookingsDone']; ?></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong><?php echo array_sum(array_column($teamLeaderTotals, 'callsDialed')); ?></strong></td>
                    <td><strong><?php echo array_sum(array_column($teamLeaderTotals, 'SiteVisitScheduled')); ?></strong></td>
                    <td><strong><?php echo array_sum(array_column($teamLeaderTotals, 'SiteVisitDone')); ?></strong></td>
                    <td><strong><?php echo array_sum(array_column($teamLeaderTotals, 'bookingsDone')); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <br>
        <form method="POST" action="logout.php">
            <input type="submit" value="Logout" class="btn btn-danger">
        </form>

        <script>
            function resetFilters() {
                document.getElementById('fromDate').value = '';
                document.getElementById('toDate').value = '';
                document.getElementById('teamLeader').value = '';
                document.forms[0].submit(); // Submit the form after resetting filters
            }
        </script>
    </div>
    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
