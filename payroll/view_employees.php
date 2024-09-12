<?php
session_start();
include('db.php'); // Database connection

// Check if the user is admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all employees from the database with department and job group information
$query = "SELECT e.*, d.designation_name AS designation_name, j.job_group AS job_group_name 
          FROM employees e 
          JOIN designation d ON e.designation_id = d.id 
          JOIN jobgroup j ON e.job_group_id = j.id 
          ORDER BY e.id ASC";
$employees = mysqli_query($conn, $query);

// Fetch departments and job groups for the dropdowns
$departments = mysqli_query($conn, "SELECT * FROM departments");
$jobgroups = mysqli_query($conn, "SELECT * FROM jobgroup");
$departments = mysqli_query($conn, "SELECT * FROM designation");


// Check if the employee ID is passed as a query parameter
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']); // Use intval to sanitize input

    // Fetch employee details from the database using a prepared statement
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if (!$employee) {
        echo "Employee not found.";
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Employee Dashboard</title>
    <style>
        body {
            padding-top: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .table td, .table th {
            vertical-align: middle;
            font-size: 12px;
        }
        .form-control {
            font-size: 9px;
            padding: 3px;
            height: auto;
        }
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
<div class="container">
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-8">
                            <h4 class="title">Employees <small>(All Data List)</small></h4>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="employee.php" class="btn btn-sm btn-primary"> Add New Employee</a>
                        </div><div></div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-hover">
        <thead>
        <tr class="active">
                <th>ID</th>
                <th>EMPLOYEE'S NAME</th>
                <th>DESIGNATION</th>
                <th>Job Group</th>
                <th>Basic Salary</th>
                <th>Medical Allowance</th>
                <th>Commuter Allowance</th>
                <th>House Allowance</th>
                <th>NSSF</th>
                <th>Housing Levy</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($employee = mysqli_fetch_assoc($employees)) { ?>
                <tr>
                    <td><?= $employee['id'] ?></td>
                    <td><?= $employee['name'] ?></td>
                    <td><?= $employee['designation_name'] ?></td>
                    <td><?= $employee['job_group_name'] ?></td>
                    <td><?= number_format($employee['basic_salary'], 2) ?></td>
                    <td><?= number_format($employee['medical_allowance'], 2) ?></td>
                    <td><?= number_format($employee['commuter_allowance'], 2) ?></td>
                    <td><?= number_format($employee['house_allowance_cluster2'], 2) ?></td>
                    <td><?= number_format($employee['nssf'], 2) ?></td>
                    <td><?= number_format($employee['affordable_housing_levy'], 2) ?></td>
                    <td>
                        <button class="btn btn-info btn-sm editBtn" data-id="<?= $employee['id'] ?>" data-toggle="modal" data-target="#editModal"><em class="fa fa-edit"></em></button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="<?= $employee['id'] ?>"><em class="fa fa-trash"></em></button>
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
<!-- Edit Employee Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm" method="POST" action="update_employee.php">
            <input type="hidden" name="employee_id" id="edit_employee_id">
            <div class="form-group">
                <label for="edit_name">Name</label>
                <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit_email">Email</label>
                <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="form-group">
                <label for="edit_pin">Pin</label>
                <input type="tel" class="form-control" id="edit_pin" name="pin_no" required>
            </div>
            <div class="form-group">
                <label for="edit_department">Department</label>
                <select class="form-control" id="edit_department" name="department" required>
                    <?php while ($department = mysqli_fetch_assoc($departments)) { ?>
                        <option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="job_group">Job Group</label>
                <select class="form-control" name="job_group" id="job_group" required>
                    <option value="" disabled selected>Select Job Group</option>
                    <?php while ($jobgroup = mysqli_fetch_assoc($jobgroups)) { ?>
                        <option value="<?= $jobgroup['id'] ?>"><?= $jobgroup['job_group'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="edit_salary">Basic Salary</label>
                <input type="number" class="form-control" id="edit_salary" name="basic_salary" required>
            </div>
            <div class="form-group">
                <label for="edit_house_allowance_cluster2">House Allowance</label>
                <input type="number" class="form-control" id="edit_house_allowance_cluster2" name="house_allowance_cluster2" required>
            </div>
            <div class="form-group">
                <label for="edit_commuter_allowance">Commuter Allowance</label>
                <input type="number" class="form-control" id="edit_commuter_allowance" name="commuter_allowance" required>
            </div>
            <div class="form-group">
                <label for="edit_medical_allowance">Medical Allowance</label>
                <input type="number" class="form-control" id="edit_medical_allowance" name="medical_allowance" required>
            </div>
            <div class="form-group">
                <label for="edit_nssf">NSSF</label>
                <input type="number" class="form-control" id="edit_nssf" name="nssf" required>
            </div>
            <div class="form-group">
                <label for="edit_affordable_housing_levy">Affordable Housing Levy</label>
                <input type="number" class="form-control" id="edit_affordable_housing_levy" name="affordable_housing_levy" required>
            </div>
            <div class="form-group">
                <label for="edit_role">Role</label>
                <select class="form-control" id="edit_role" name="role" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Employee</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script to handle Edit and Delete functionality -->
<script>
    $('#manageEmployeesLink').on('click', function () {
                $('#content-area').load('manage_employees.php');
            });
    $(document).ready(function() {
        // Handle Edit Button Click
        $('.editBtn').on('click', function() {
            var employeeId = $(this).data('id');

            // Fetch employee details using AJAX
            $.ajax({
                url: 'get_employee.php',
                type: 'GET',
                data: { id: employeeId },
                success: function(data) {
                    var employee = JSON.parse(data);

                    // Populate the form fields in the modal with the employee data
                    $('#edit_employee_id').val(employee.id);
                    $('#edit_name').val(employee.name);
                    $('#edit_email').val(employee.email);
                    $('#edit_pin').val(employee.pin_no);
                    $('#edit_department').val(employee.department_id);
                    $('#job_group').val(employee.job_group_id);
                    $('#edit_salary').val(employee.basic_salary);
                    $('#edit_house_allowance_cluster2').val(employee.house_allowance_cluster2);
                    $('#edit_commuter_allowance').val(employee.commuter_allowance);
                    $('#edit_medical_allowance').val(employee.medical_allowance);
                    $('#edit_nssf').val(employee.nssf);
                    $('#edit_affordable_housing_levy').val(employee.affordable_housing_levy);
                    $('#edit_role').val(employee.role);
                }
            });
        });

        // Open delete confirmation modal and set employee ID
        $('.deleteBtn').on('click', function() {
            var employeeId = $(this).data('id');
            $('#employee_id').val(employeeId);
            $('#deleteModal').modal('show');
        });
    });
</script>

</body>
</html>
