<?php
// Check if the admin is not logged in, redirect to the login page
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_data.php");
    exit;
}
?>

<?php
// Include the database configuration file
require_once 'database.php';

// Retrieve employee data from the database
$sql = "SELECT * FROM contacts";
$result = $conn->query($sql);

// Retrieve distinct employee names from the database
$sqlEmployeeNames = "SELECT DISTINCT employeeName FROM contacts";
$resultEmployeeNames = $conn->query($sqlEmployeeNames);

// Retrieve distinct team leaders from the database
$sqlTeamLeaders = "SELECT DISTINCT teamLeader FROM contacts";
$resultTeamLeaders = $conn->query($sqlTeamLeaders);

// Filter variables
$filterFromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$filterToDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$filterEmployeeName = isset($_GET['employee_name']) ? $_GET['employee_name'] : '';
$filterTeamLeader = isset($_GET['team_leader']) ? $_GET['team_leader'] : '';

// Apply filters to the SQL query
$filterQuery = "SELECT * FROM contacts WHERE 1=1";
if ($filterFromDate != '') {
    $filterQuery .= " AND dateandTime >= '$filterFromDate'";
}
if ($filterToDate != '') {
    $nextDay = date('Y-m-d', strtotime($filterToDate . '+1 day'));
    $filterQuery .= " AND dateandTime < '$nextDay'";
}
if ($filterEmployeeName != '') {
    $filterQuery .= " AND employeeName = '$filterEmployeeName'";
}
if ($filterTeamLeader != '') {
    $filterQuery .= " AND teamLeader = '$filterTeamLeader'";
}


// Retrieve filtered employee data from the database
$resultFiltered = $conn->query($filterQuery);
?>
<?php
function calculateTotal($columnName)
{
    global $filterQuery, $conn;
    $totalQuery = "SELECT SUM($columnName) AS total FROM ($filterQuery) AS filtered";
    $resultTotal = $conn->query($totalQuery);
    $rowTotal = $resultTotal->fetch_assoc();
    return $rowTotal['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee View</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"> 
    <style>
        /* Custom CSS Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <div class="container content">
        <h2>Employee View</h2>

        <form method="GET" action="">
            <div class="row g-3">
                <div class="col-md-2">
                    <label for="from_date" class="form-label">From Date:</label>
                    <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo $filterFromDate; ?>">
                </div>
                <div class="col-md-2">
                    <label for="to_date" class="form-label">To Date:</label>
                    <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo $filterToDate; ?>">
                </div>
                <div class="col-md-2">
                    <label for="employee_name" class="form-label">Employee Name:</label>
                    <select id="employee_name" name="employee_name" class="form-control">
                        <option value="">All</option>
                        <?php
                        while ($rowEmployeeNames = $resultEmployeeNames->fetch_assoc()) {
                            $selected = ($filterEmployeeName == $rowEmployeeNames['employeeName']) ? 'selected' : '';
                            echo '<option value="' . $rowEmployeeNames['employeeName'] . '" ' . $selected . '>' . $rowEmployeeNames['employeeName'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="team_leader" class="form-label">Team Leader:</label>
                    <select id="team_leader" name="team_leader" class="form-control">
                        <option value="">All</option>
                        <?php
                        while ($rowTeamLeaders = $resultTeamLeaders->fetch_assoc()) {
                            $selected = ($filterTeamLeader == $rowTeamLeaders['teamLeader']) ? 'selected' : '';
                            echo '<option value="' . $rowTeamLeaders['teamLeader'] . '" ' . $selected . '>' . $rowTeamLeaders['teamLeader'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mt-4 d-flex align-items-end">
                <div class="me-2">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
                <div>
                    <button class="btn btn-secondary"><a href="employee_view.php" class="text-white text-decoration-none">Reset Filter</a></button>
                </div>
            </div>

            </div>
        </form>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Date and Time</th>
                    <th>Employee Name</th>
                    <th>Team Leader</th>
                    <th>Calls Dialed</th>
                    <th>Site Visit Done</th>
                    <th>Site Visit Scheduled</th>
                    <th>Bookings Done</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowFiltered = $resultFiltered->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $rowFiltered['dateandTime'] . '</td>';
                    echo '<td>' . $rowFiltered['employeeName'] . '</td>';
                    echo '<td>' . $rowFiltered['teamLeader'] . '</td>';
                    echo '<td>' . $rowFiltered['callsDialed'] . '</td>';
                    echo '<td>' . $rowFiltered['SiteVisitDone'] . '</td>';
                    echo '<td>' . $rowFiltered['SiteVisitScheduled'] . '</td>';
                    echo '<td>' . $rowFiltered['bookingsDone'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?php echo calculateTotal('callsDialed'); ?></strong></td>
                    <td><strong><?php echo calculateTotal('SiteVisitDone'); ?></strong></td>
                    <td><strong><?php echo calculateTotal('SiteVisitScheduled'); ?></strong></td>
                    <td><strong><?php echo calculateTotal('bookingsDone'); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        <br>
        <form method="POST" action="logout.php">
            <input type="submit" value="Logout" class="btn btn-danger">
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Hide specific links with the 'desktop-link' class in mobile view
            if ($(window).width() <= 768) {
                $('.desktop-link').hide();
            }
        });
    </script>
</body>
</html>
