<?php
session_start();
session_unset(); // remove all session variables
session_destroy(); // destroy the session

// Redirect to login page using JS
echo "<script>window.location.href = 'login.php';</script>";
exit;
?>
