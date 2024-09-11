<?php include('db.php'); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    $query = "INSERT INTO attendance (student_id, course_id, attendance_date, status)
              VALUES ('$student_id', '$course_id', '$attendance_date', '$status')";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Attendance</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" name="student_id" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="course_id">Course</label>
                <select name="course_id" class="form-control">
                    <?php
                    $query = "SELECT * FROM courses";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="attendance_date">Attendance Date</label>
                <input type="date" name="attendance_date" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="status">Attendance Status</label>
                <select name="status" class="form-control">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Submit Attendance</button>
        </form>

        <h3 class="mt-5">Attendance Records</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Attendance ID</th>
                    <th>Student ID</th>
                    <th>Course Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT attendance.attendance_id, attendance.student_id, courses.course_name, attendance.attendance_date, attendance.status
                          FROM attendance
                          JOIN courses ON attendance.course_id = courses.course_id";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['attendance_id']}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['course_name']}</td>
                            <td>{$row['attendance_date']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
