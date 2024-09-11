<?php
session_start();
include('db.php');

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}



// Fetch all deductions from the database
$query = "SELECT d.*, e.name AS employee_name FROM deductions d 
          JOIN employees e ON d.employee_id = e.id";
$deductions = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee Deductions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
             .demo{ font-family: 'Poppins', sans-serif; }
.panel{
    padding: 10px;
    border-radius: 0;
}
.panel .panel-heading{ padding: 20px 15px 0; }
.panel .panel-heading .title{
    color: #222;
    font-size: 20px;
    font-weight: 600;
    line-height: 30px;
    margin: 0;
}
.panel .panel-heading .btn{
    background-color: #6D7AE0;
    padding: 6px 12px;
    border-radius: 0;
    border: none;
    transition: all 0.3s ease 0s;
}
.panel .panel-heading .btn:hover{
    text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
    box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.08);
}
.panel .panel-body .table{
    margin: 0;
    border: 1px solid #e7e7e7;
}
.panel .panel-body .table tr td{ border-color: #e7e7e7; }
.panel .panel-body .table thead tr.active th{
    color: #fff;
    background-color: #6D7AE0;
    font-size: 16px;
    font-weight: 500;
    padding: 12px;
    border: 1px solid #6D7AE0;
}
.panel .panel-body .table tbody tr:hover{ background-color: rgba(109, 122, 224, 0.1); }
.panel .panel-body .table tbody tr td{
    color: #555;
    padding: 8px 12px;
    vertical-align: middle;
}
.panel .panel-body .table tbody .btn{
    border-radius: 0;
    transition: all 0.3s ease;
}
.panel .panel-body .table tbody .btn:hover{ box-shadow: 0 0 0 2px #333; }
.panel .panel-footer{
    background-color: #fff;
    padding: 0 15px 5px;
    border: none;
}
.panel .panel-footer .col{ line-height: 35px; }
.pagination{ margin: 0; }
.pagination li a{
    font-size: 15px;
    font-weight: 600;
    color: #6D7AE0;
    text-align: center;
    height: 35px;
    width: 35px;
    line-height: 33px;
    padding: 0;
    margin: 0 2px;
    display: block;
    transition: all 0.3s ease 0s;
}
.pagination li:first-child a,
.pagination li:last-child a{
    border-radius: 0;
}
.pagination li a:hover,
.pagination li a:focus,
.pagination li.active a{
    color: #fff;
    background-color: #6D7AE0;
}
    </style>
</head>
<body>
    
    <div class="container mt-5">
        <h2></h2>
        <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-8">
                            <h4 class="title">Edit Employee Deductions <small>(All Data List)</small></h4>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="#" class="btn btn-sm btn-primary"> Create New</a>
                        </div><br>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-hover">
        <thead>
        <tr class="active">
                    <th>#</th> <!-- New column for index -->
                    <th>Employee Name</th>
                    <th>NSSF</th>
                    <th>NHIF</th>
                    <th>PAYE</th>
                    <th>Affordable Housing</th>
                    <th>FamilyBank Loan</th>
                    <th>Welfare</th>
                    <th>Kudheiha</th>
                    <th>Advance</th>
                    <th>Rent</th>
                    <th>Imarika Sacco</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1; // Initialize the counter
                while ($row = mysqli_fetch_assoc($deductions)) { ?>
                    <tr>
                        <td><?= $counter++; ?></td> <!-- Display the row number -->
                        <td><?= htmlspecialchars($row['employee_name']); ?></td>
                        <td><?= number_format($row['NSSF'], 2); ?></td>
                        <td><?= number_format($row['NHIF'], 2); ?></td>
                        <td><?= number_format($row['paye'], 2); ?></td>
                        <td><?= number_format($row['affordable_housing_levy'], 2); ?></td>
                        <td><?= number_format($row['family_bank_loan'], 2); ?></td>
                        <td><?= number_format($row['welfare'], 2); ?></td>
                        <td><?= number_format($row['kudheiha'], 2); ?></td>
                        <td><?= number_format($row['advance'], 2); ?></td>
                        <td><?= number_format($row['rent'], 2); ?></td>
                        <td><?= number_format($row['imarika_sacco'], 2); ?></td>
                        <td>
                            <!-- Edit button -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editModal<?= $row['employee_id']; ?>">Edit</button>
                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['employee_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Deductions for <?= htmlspecialchars($row['employee_name']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="update_deduction.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="employee_id" value="<?= $row['employee_id']; ?>">
                                                <div class="form-group">
                                                    <label for="NSSF">NSSF</label>
                                                    <input type="text" class="form-control" name="NSSF" value="<?= htmlspecialchars($row['NSSF']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="NHIF">NHIF</label>
                                                    <input type="text" class="form-control" name="NHIF" value="<?= htmlspecialchars($row['NHIF']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="PAYE">PAYE</label>
                                                    <input type="text" class="form-control" name="paye" value="<?= htmlspecialchars($row['paye']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="affordable_housing_levy">Affordable Housing Levy</label>
                                                    <input type="text" class="form-control" name="affordable_housing_levy" value="<?= htmlspecialchars($row['affordable_housing_levy']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="family_bank_loan">Family Bank Loan</label>
                                                    <input type="text" class="form-control" name="family_bank_loan" value="<?= htmlspecialchars($row['family_bank_loan']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="welfare">Welfare</label>
                                                    <input type="text" class="form-control" name="welfare" value="<?= htmlspecialchars($row['welfare']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kudheiha">Kudheiha</label>
                                                    <input type="text" class="form-control" name="kudheiha" value="<?= htmlspecialchars($row['kudheiha']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="advance">Advance</label>
                                                    <input type="text" class="form-control" name="advance" value="<?= htmlspecialchars($row['advance']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="rent">Rent</label>
                                                    <input type="text" class="form-control" name="rent" value="<?= htmlspecialchars($row['rent']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="imarika_sacco">Imarika Sacco</label>
                                                    <input type="text" class="form-control" name="imarika_sacco" value="<?= htmlspecialchars($row['imarika_sacco']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" name="update_deductions">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
                    <div class="row">
                        <div class="col col-xs-4">Page 1 of 5</div>
                        <div class="col-xs-8">
                            <ul class="pagination hidden-xs pull-right">
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                            </ul>
                            <ul class="pagination visible-xs pull-right">
                                <li><a href="#"><<</a></li>
                                <li><a href="#">>></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
