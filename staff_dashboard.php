<?php
session_start();
if ($_SESSION['role'] != 'staff') {
    header('Location: login.html');
    exit();
}
include('db.php');

$classes = mysqli_query($conn, "SELECT * FROM classes WHERE teacher_id = '".$_SESSION['user_id']."'");

if (isset($_POST['mark_attendance'])) {
    $date = $_POST['date'];
    $student_ids = $_POST['student_ids'];
    foreach ($student_ids as $student_id) {
        $status = $_POST['status_' . $student_id];
        mysqli_query($conn, "INSERT INTO attendance (student_id, date, status) VALUES ('$student_id', '$date', '$status')");
    }
    echo "Attendance marked successfully.";
}
?>

<h3>Mark Attendance</h3>
<form method="POST" action="">
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" class="form-control" name="date" required>
    </div>

    <div class="form-group">
        <label for="class">Select Class</label>
        <select class="form-control" name="class_id" required>
            <?php while ($class = mysqli_fetch_assoc($classes)) { ?>
                <option value="<?= $class['id'] ?>"><?= $class['class_name'] ?> - <?= $class['subject'] ?></option>
            <?php } ?>
        </select>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $students = mysqli_query($conn, "SELECT * FROM students WHERE class = '".$_POST['class_id']."'");
            while ($student = mysqli_fetch_assoc($students)) { ?>
                <tr>
                    <td><?= $student['name'] ?></td>
                    <td>
                        <select name="status_<?= $student['id'] ?>" class="form-control">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </td>
                    <input type="hidden" name="student_ids[]" value="<?= $student['id'] ?>">
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <button type="submit" name="mark_attendance" class="btn btn-primary">Submit Attendance</button>
</form>
<h3>Enter Marks</h3>
<form method="POST" action="">
    <div class="form-group">
        <label for="class">Select Class</label>
        <select class="form-control" name="class_id" required>
            <?php while ($class = mysqli_fetch_assoc($classes)) { ?>
                <option value="<?= $class['id'] ?>"><?= $class['class_name'] ?> - <?= $class['subject'] ?></option>
            <?php } ?>
        </select>
    </div>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $students = mysqli_query($conn, "SELECT * FROM students WHERE class = '".$_POST['class_id']."'");
            while ($student = mysqli_fetch_assoc($students)) { ?>
                <tr>
                    <td><?= $student['name'] ?></td>
                    <td><input type="number" name="marks_<?= $student['id'] ?>" class="form-control" required></td>
                    <td><input type="number" name="total_marks_<?= $student['id'] ?>" class="form-control" required></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <button type="submit" name="enter_marks" class="btn btn-primary">Submit Marks</button>
</form>

<?php
if (isset($_POST['enter_marks'])) {
    $class_id = $_POST['class_id'];
    $student_ids = $_POST['student_ids'];
    foreach ($student_ids as $student_id) {
        $marks_obtained = $_POST['marks_' . $student_id];
        $total_marks = $_POST['total_marks_' . $student_id];
        mysqli_query($conn, "INSERT INTO marks (student_id, class_id, marks_obtained, total_marks, date) VALUES ('$student_id', '$class_id', '$marks_obtained', '$total_marks', NOW())");
    }
    echo "Marks entered successfully.";
}
?>
