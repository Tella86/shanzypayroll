<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.html');
    exit();
}
include('db.php');

$students = mysqli_query($conn, "SELECT * FROM students");
$staff = mysqli_query($conn, "SELECT * FROM staff");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Welcome Admin</h2>
        <div class="row">
            <div class="col-md-6">
                <h3>Manage Students</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = mysqli_fetch_assoc($students)) { ?>
                            <tr>
                                <td><?= $student['name'] ?></td>
                                <td><?= $student['class'] ?></td>
                                <td>
                                    <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="delete_student.php?id=<?= $student['id'] ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h3>Manage Staff</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($staff_member = mysqli_fetch_assoc($staff)) { ?>
                            <tr>
                                <td><?= $staff_member['name'] ?></td>
                                <td><?= $staff_member['department'] ?></td>
                                <td>
                                    <a href="edit_staff.php?id=<?= $staff_member['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="delete_staff.php?id=<?= $staff_member['id'] ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
