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

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $new_grade = $_POST['new_grade'];

    // Prepare statement to update grade in Grade table
    $stmt = mysqli_prepare($conn, "UPDATE Grade SET grade = ? WHERE student_id = ? AND course_id = ?");
    if (!$stmt) {
        die("Error preparing update statement: " . mysqli_error($conn));
    }

    // Bind parameters for update
    mysqli_stmt_bind_param($stmt, "ssi", $new_grade, $student_id, $course_id);

    // Execute update
    if (mysqli_stmt_execute($stmt)) {
        // Fetch student's first name and last name
        $student_query = "SELECT first_name, last_name FROM Student WHERE student_id = ?";
        $student_stmt = mysqli_prepare($conn, $student_query);
        if (!$student_stmt) {
            die("Error preparing student query: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($student_stmt, "i", $student_id);
        mysqli_stmt_execute($student_stmt);
        mysqli_stmt_bind_result($student_stmt, $first_name, $last_name);
        mysqli_stmt_fetch($student_stmt);
        mysqli_stmt_close($student_stmt);

        echo "Grade updated successfully.<br>";
        echo "Student ID: $student_id<br>";
        echo "Student Name: $first_name $last_name<br>";
        echo "Course ID: $course_id<br>";
        echo "New Grade: $new_grade";
    } else {
        echo "Error updating grade: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>
