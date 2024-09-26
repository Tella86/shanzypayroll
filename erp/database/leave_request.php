<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Leave Request</h2>
        <?php
        include 'db.php';

        if (isset($_POST['submit'])) {
            $employee_id = $_POST['employee_id'];
            $leave_type = $_POST['leave_type'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            $query = "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date) 
                      VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isss", $employee_id, $leave_type, $start_date, $end_date);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Leave request submitted successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
            }
        }
        ?>

        <form method="post" action="leave_request.php">
            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee ID</label>
                <input type="number" class="form-control" id="employee_id" name="employee_id" required>
            </div>
            <div class="mb-3">
                <label for="leave_type" class="form-label">Leave Type</label>
                <select class="form-select" id="leave_type" name="leave_type" required>
                    <option value="sick">Sick</option>
                    <option value="vacation">Vacation</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>
</html>
