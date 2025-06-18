<form action="" method="post">
    <input type="text" name="pincode" placeholder="Enter Pincode">
    <input type="submit" name="submit" value="Submit">
</form>

<?php
// Always check if the form is submitted before processing
if (isset($_POST['submit'])) {
    include('config/get_coordinates.php'); // Missing semicolon in your code

    $pincode = $_POST['pincode']; // You wrote $pincodex, should match this variable
    $coordinates = getCoordinates($pincode); // Corrected variable name

    if ($coordinates) {
        $latitude = $coordinates['lat'];
        $longitude = $coordinates['lon'];

        echo "Latitude: $latitude<br>";
        echo "Longitude: $longitude<br>";
    } else {
        echo "Coordinates not found for this pincode.";
    }
}
?>
