<?php
include 'db.php'; // Ensure db.php contains your database connection
$employees = mysqli_query($conn, "SELECT * FROM employees"); // Renamed to $employees for consistency
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Deductions Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Employee Deductions Form</h2>
        <form method="POST" action="process_deductions.php">
            <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <select class="form-control" name="employee_id" required>
                    <option value="" disabled selected>Select employee_id</option>
                    <?php while ($employee = mysqli_fetch_assoc($employees)) { ?>
                    <option value="<?= $employee['id'] ?>"><?= $employee['id'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nssf">NSSF</label>
                <input type="number" class="form-control" id="nssf" name="nssf" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="nhif">NHIF</label>
                <input type="number" class="form-control" id="nhif" name="nhif" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="paye">PAYE</label>
                <input type="number" class="form-control" id="paye" name="paye" value="0.00" step="0.01" readonly>
            </div>

            <div class="form-group">
                <label for="affordable_housing_levy">Affordable Housing Levy</label>
                <input type="number" class="form-control" id="affordable_housing_levy" name="affordable_housing_levy" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="family_bank_loan">Family Bank Loan</label>
                <input type="number" class="form-control" id="family_bank_loan" name="family_bank_loan" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="welfare">Welfare</label>
                <input type="number" class="form-control" id="welfare" name="welfare" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="kudheiha">Kudheiha</label>
                <input type="number" class="form-control" id="kudheiha" name="kudheiha" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="advance">Advance</label>
                <input type="number" class="form-control" id="advance" name="advance" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="rent">Rent</label>
                <input type="number" class="form-control" id="rent" name="rent" value="0.00" step="0.01">
            </div>

            <div class="form-group">
                <label for="imarika_sacco">Imarika Sacco</label>
                <input type="number" class="form-control" id="imarika_sacco" name="imarika_sacco" value="0.00" step="0.01">
            </div>

            <button type="submit" class="btn btn-primary">Submit Deductions</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
