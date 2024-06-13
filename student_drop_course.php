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

// Function to convert course code to uppercase
function sanitizeCourseCode($course_code) {
    return strtoupper($course_code);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $student_id = $_POST['student_id'];
    $drop_course_code = sanitizeCourseCode($_POST['course_code']);

    // Check if the course code exists
    $check_query = "SELECT course_id FROM Course WHERE UPPER(course_code) = '$drop_course_code'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Course exists, retrieve course_id
        $row = mysqli_fetch_assoc($result);
        $course_id = $row['course_id'];

        // Check if the student is enrolled in the course
        $check_enrollment_query = "SELECT * FROM Enrollment WHERE student_id = $student_id AND course_id = $course_id";
        $enrollment_result = mysqli_query($conn, $check_enrollment_query);

        if (mysqli_num_rows($enrollment_result) > 0) {
            // Student is enrolled, delete enrollment
            $delete_query = "DELETE FROM Enrollment WHERE student_id = $student_id AND course_id = $course_id";
            if (mysqli_query($conn, $delete_query)) {
                echo "Course dropped successfully.";
            } else {
                echo "Error dropping course: " . mysqli_error($conn);
            }
        } else {
            echo "You are not enrolled in this course.";
        }
    } else {
        echo "Course not found.";
    }
}

mysqli_close($conn);
?>

