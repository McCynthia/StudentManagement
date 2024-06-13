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
    $course_code = strtoupper($_POST['course_code']); // Convert course code to uppercase

    // Prepare statement to delete corresponding enrollments
    $stmt_enrollment = mysqli_prepare($conn, "DELETE FROM Enrollment WHERE course_id IN (SELECT course_id FROM Course WHERE course_code = ?)");
    if (!$stmt_enrollment) {
        die("Error preparing enrollment deletion statement: " . mysqli_error($conn));
    }

    // Bind parameters for enrollment deletion
    mysqli_stmt_bind_param($stmt_enrollment, "s", $course_code);

    // Execute enrollment deletion
    if (mysqli_stmt_execute($stmt_enrollment)) {
        // Prepare statement to delete course from Course table
        $stmt_course = mysqli_prepare($conn, "DELETE FROM Course WHERE course_code = ?");
        if (!$stmt_course) {
            die("Error preparing course deletion statement: " . mysqli_error($conn));
        }

        // Bind parameters for course deletion
        mysqli_stmt_bind_param($stmt_course, "s", $course_code);

        // Execute course deletion
        if (mysqli_stmt_execute($stmt_course)) {
            echo "Course deleted successfully and corresponding enrollments removed.";
        } else {
            echo "Error deleting course: " . mysqli_error($conn);
        }

        // Close course statement
        mysqli_stmt_close($stmt_course);
    } else {
        echo "Error deleting enrollments: " . mysqli_error($conn);
    }

    // Close enrollment statement
    mysqli_stmt_close($stmt_enrollment);
}

// Close connection
mysqli_close($conn);
?>