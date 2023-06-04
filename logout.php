<?php
session_start();

// Destroy the session and unset all session variables
session_unset();
session_destroy();

// Redirect to the index.php page after logout
header("Location: index.php");
exit;
?>
