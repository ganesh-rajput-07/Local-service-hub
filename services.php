<?php
include('config/db_connect.php');
session_start();

$result = $conn->query("SELECT * FROM services WHERE status='active'");

echo "<h2>Available Services</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
    echo "<h3>" . $row['title'] . "</h3>";
    echo "<p>" . $row['description'] . "</p>";
    echo "<p>Price: ₹" . $row['price'] . "</p>";
    echo "<p>Average Rating: " . number_format($row['average_rating'], 1) . " ⭐</p>";
    echo "<a href='booking.php?service_id=" . $row['id'] . "'>Book Now</a>";
    echo "</div>";
}
?>
