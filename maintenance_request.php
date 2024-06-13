<?php
// Database connection details
$servername = "ix.cs.uoregon.edu";
$username = "guest";
$password = "guest";
$dbname = "StudentManagement";
$port = "3721";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port) 
        or die('Error connecting to MySQL server.');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully to the database.<br>";
}

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $classroom_id = $_POST['classroom_id'];
    $maintenance_req = isset($_POST['maintenance']) ? 1 : 0; // Convert checkbox value to boolean (1/0)
    $details = $_POST['details'];

    // Check if the classroom exists
    $check_query = "SELECT * FROM Classroom WHERE classroom_id = '$classroom_id'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Classroom exists, proceed with updating maintenance details
        $update_query = "UPDATE Classroom SET maintenance_req = $maintenance_req, details = '$details' WHERE classroom_id = '$classroom_id'";

        if (mysqli_query($conn, $update_query)) {
            echo "Maintenance request updated successfully for Classroom ID: $classroom_id.<br>";
            echo "Details: $details<br>";
            echo "Maintenance Required: " . ($maintenance_req ? 'Yes' : 'No') . "<br>";
        } else {
            echo "Error updating maintenance request: " . mysqli_error($conn);
        }
    } else {
        echo "Classroom with ID: $classroom_id does not exist. Please enter a valid Classroom ID.<br>";
    }
}

// Close connection
mysqli_close($conn);
?>
