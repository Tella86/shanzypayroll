<?php include('db.php'); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $department_id = $_POST['department_id'];

    $query = "INSERT INTO courses (course_name, department_id) VALUES ('$course_name', '$department_id')";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Courses</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="course_name">Course Name</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="department_id">Select Department</label>
                <select name="department_id" class="form-control">
                    <?php
                    $query = "SELECT * FROM departments";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Add Course</button>
        </form>

        <h3 class="mt-5">List of Courses</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT courses.course_id, courses.course_name, departments.department_name
                          FROM courses
                          JOIN departments ON courses.department_id = departments.department_id";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['course_id']}</td>
                            <td>{$row['course_name']}</td>
                            <td>{$row['department_name']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
