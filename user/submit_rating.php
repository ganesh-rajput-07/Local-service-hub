<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$service_id = $_POST['service_id'];
$rating = $_POST['rating'];
$review = mysqli_real_escape_string($conn, $_POST['review']);

// Check if already rated
$check = $conn->query("SELECT * FROM service_ratings WHERE service_id='$service_id' AND user_id='$user_id'");
if ($check->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already rated this service.']);
    exit();
}

$conn->query("INSERT INTO service_ratings (service_id, user_id, rating, review) VALUES ('$service_id', '$user_id', '$rating', '$review')");

// Update Average Rating
$avg_result = $conn->query("SELECT AVG(rating) AS avg_rating FROM service_ratings WHERE service_id='$service_id'");
$avg_rating = round($avg_result->fetch_assoc()['avg_rating'], 1);
$conn->query("UPDATE services SET average_rating='$avg_rating' WHERE id='$service_id'");

echo json_encode(['status' => 'success', 'message' => 'Thank you for your rating!', 'avg_rating' => $avg_rating]);
exit();
?>
