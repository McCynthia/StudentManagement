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
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Delete student from the database
    $sql = "DELETE FROM Student WHERE first_name = ? AND last_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $first_name, $last_name);

    if ($stmt->execute()) {
        echo "Student deleted successfully";
    } else {
        echo "Error deleting student: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
