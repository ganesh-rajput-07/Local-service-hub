<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');

    exit();
}
include('../config/db_connect.php');

$id = $_GET['id'];
$conn->query("DELETE FROM categories WHERE id='$id'");

echo "<script>alert('Category Deleted Successfully'); window.location.href='manage_category.php';</script>";
?>
