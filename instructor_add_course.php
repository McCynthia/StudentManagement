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
    $course_name = $_POST['course_name'];
    $course_code = strtoupper($_POST['course_code']); // Convert course code to uppercase
    $description = $_POST['description'];
    $credits = $_POST['credits'];
    $schedule = $_POST['schedule'];
    $department_id = $_POST['department_id'];
    $instructor_id = $_POST['instructor_id'];
    $classroom_id = $_POST['classroom_id'];

    // Prepare statement
    $stmt = mysqli_prepare($conn, "INSERT INTO Course (course_name, course_code, description, credits, schedule, department_id, instructor_id, classroom_id>
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssssss", $course_name, $course_code, $description, $credits, $schedule, $department_id, $instructor_id, $classroom_id);

    // Execute statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Course added successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>
