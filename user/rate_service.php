<?php
include('../config/db_connect.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_POST['submit_rating'])) {
    $user_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Insert rating
    $conn->query("INSERT INTO service_ratings (service_id, user_id, rating, review) VALUES ('$service_id', '$user_id', '$rating', '$review')");

    // Update average rating for this service
    $avg_result = $conn->query("SELECT AVG(rating) AS avg_rating FROM service_ratings WHERE service_id='$service_id'");
    $avg_rating = $avg_result->fetch_assoc()['avg_rating'];
    $conn->query("UPDATE services SET average_rating='$avg_rating' WHERE id='$service_id'");

    // Update vendor's overall average rating
    $vendor_result = $conn->query("SELECT vendor_id FROM services WHERE id='$service_id'");
    $vendor_id = $vendor_result->fetch_assoc()['vendor_id'];

    $vendor_avg_result = $conn->query("SELECT AVG(average_rating) AS vendor_avg FROM services WHERE vendor_id='$vendor_id'");
    $vendor_avg = $vendor_avg_result->fetch_assoc()['vendor_avg'];
    $conn->query("UPDATE vendors SET vendor_avg_rating='$vendor_avg' WHERE id='$vendor_id'");

    header('Location: dashboard.php');
    exit();
}
?>
