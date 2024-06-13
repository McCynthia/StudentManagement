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
    // Collect form data
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    // Check if student_id exists
    $check_student_query = "SELECT * FROM Student WHERE student_id = ?";
    $stmt_student = mysqli_prepare($conn, $check_student_query);
    mysqli_stmt_bind_param($stmt_student, "i", $student_id);
    mysqli_stmt_execute($stmt_student);
    $student_result = mysqli_stmt_get_result($stmt_student);

    // Check if course_id exists
    $check_course_query = "SELECT * FROM Course WHERE course_id = ?";
    $stmt_course = mysqli_prepare($conn, $check_course_query);
    mysqli_stmt_bind_param($stmt_course, "i", $course_id);
    mysqli_stmt_execute($stmt_course);
    $course_result = mysqli_stmt_get_result($stmt_course);

    // If both student_id and course_id exist, update attendance
    if (mysqli_num_rows($student_result) > 0 && mysqli_num_rows($course_result) > 0) {
        // Prepare statement to update attendance status
        $stmt = mysqli_prepare($conn, "UPDATE Attendance SET status = ? WHERE student_id = ? AND course_id = ? AND date = ?");
        if (!$stmt) {
            die("Error preparing update statement: " . mysqli_error($conn));
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "siss", $status, $student_id, $course_id, $date);

        // Execute statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Attendance marked successfully.<br>";
            echo "Student ID: $student_id<br>";
            echo "Course ID: $course_id<br>";
            echo "Date: $date<br>";
            echo "Status: $status<br>";
        } else {
            echo "Error updating attendance: " . mysqli_error($conn);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Student or course does not exist. Attendance not marked.";
    }

    // Close result sets
    mysqli_free_result($student_result);
    mysqli_free_result($course_result);

    // Close prepared statements
    mysqli_stmt_close($stmt_student);
    mysqli_stmt_close($stmt_course);
}

// Close connection
mysqli_close($conn);
?>
