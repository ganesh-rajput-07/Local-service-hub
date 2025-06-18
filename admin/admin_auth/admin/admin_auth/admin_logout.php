<?php
session_start();
session_destroy();

// Redirect to admin login page
header('Location: admin_login.php');
exit();
?>
