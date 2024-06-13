<?php
// Database connection details
$servername = "ix.cs.uoregon.edu";
$username = "guest";
$password = "guest";
$dbname = "StudentManagement";
$port = "3721";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch table name from the query string
$table = $_GET['table'];

// Prepare SQL query to fetch all rows from the selected table
$sql = "SELECT * FROM $table";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error fetching data: ' . mysqli_error($conn));
}

// Fetch all rows from the result set
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Close connection
mysqli_close($conn);

// Return data as JSON format
header('Content-Type: application/json');
echo json_encode($data);
?>
