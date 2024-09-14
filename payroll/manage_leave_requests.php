<?php
session_start();
include('db.php');

// Ensure the user is logged in as an admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all leave requests
$query = "SELECT lr.*, e.name AS employee_name, lt.name AS leave_type 
          FROM leave_requests lr
          JOIN employees e ON lr.employee_id = e.id
          JOIN leave_types lt ON lr.leave_type_id = lt.id
          ORDER BY lr.request_date DESC";
$leave_requests = mysqli_query($conn, $query);

// Handle approval and rejection
if (isset($_POST['approve_leave'])) {
    $leave_id = $_POST['leave_id'];
    mysqli_query($conn, "UPDATE leave_requests SET status = 'Approved' WHERE id = '$leave_id'");
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh the page after approval
    exit();
} elseif (isset($_POST['reject_leave'])) {
    $leave_id = $_POST['leave_id'];
    mysqli_query($conn, "UPDATE leave_requests SET status = 'Rejected' WHERE id = '$leave_id'");
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh the page after rejection
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
<?php include '../leave/header.php';?>

<div class="container">
    <h2>Manage Leave Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($leave_request = mysqli_fetch_assoc($leave_requests)) { ?>
                <tr>
                    <td><?= $leave_request['employee_name'] ?></td>
                    <td><?= $leave_request['leave_type'] ?></td>
                    <td><?= date('d-M-Y', strtotime($leave_request['start_date'])) ?></td>
                    <td><?= date('d-M-Y', strtotime($leave_request['end_date'])) ?></td>
                    <td><?= $leave_request['reason'] ?></td>
                    <td><?= ucfirst($leave_request['status']) ?></td>
                    <td>
                        <?php if ($leave_request['status'] == 'Pending') { ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="leave_id" value="<?= $leave_request['id'] ?>">
                                <button type="submit" name="approve_leave" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="reject_leave" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        <?php } else { ?>
                            <span class="text-muted"><?= ucfirst($leave_request['status']) ?></span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
