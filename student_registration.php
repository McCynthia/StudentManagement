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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Prepare statement
    $stmt = mysqli_prepare($conn, "INSERT INTO Student (first_name, last_name, email, date_of_birth, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $email, $date_of_birth, $phone, $address);

    // Execute statement
    if (mysqli_stmt_execute($stmt)) {
        echo "New student registered successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>