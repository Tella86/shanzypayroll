<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header('Location: login.html');
    exit();
}

include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute student query using prepared statements
$stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit();
}

// Prepare and execute attendance query
$attendance_stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = ?");
$attendance_stmt->bind_param("i", $student['id']);
$attendance_stmt->execute();
$attendance_records = $attendance_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($student['name']) ?></h2>
        <p>Class: <?= htmlspecialchars($student['class']) ?></p>
        <p>View your attendance, marks, and upcoming events.</p>
    </div>

    <h3>Attendance Records</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($record = $attendance_records->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($record['date']) ?></td>
                    <td><?= htmlspecialchars($record['status']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php
// Prepare and execute marks query
$marks_stmt = $conn->prepare("SELECT * FROM marks WHERE student_id = ?");
$marks_stmt->bind_param("i", $student['id']);
$marks_stmt->execute();
$marks_records = $marks_stmt->get_result();
?>

    <h3>Marks Records</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($record = $marks_records->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($record['date']) ?></td>
                    <td><?= htmlspecialchars($record['subject']) ?></td>
                    <td><?= htmlspecialchars($record['marks_obtained']) ?></td>
                    <td><?= htmlspecialchars($record['total_marks']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php
// Close the prepared statements and database connection
$stmt->close();
$attendance_stmt->close();
$marks_stmt->close();
$conn->close();
?>

</body>
</html>
