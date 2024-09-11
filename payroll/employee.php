<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Manage Employees</h2>

        <!-- Button to trigger the modal -->
        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addEmployeeModal">Add New Employee</a>

        <!-- Modal -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Register Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="reg_employees.php">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="pin_no">PIN</label>
                                <input type="text" class="form-control" name="pin_no" required>
                            </div>
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select class="form-control" name="department" required>
                                    <option value="" disabled selected>Select Department</option>
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
                                <label for="basic_salary">Basic Salary</label>
                                <select class="form-control" name="basic_salary" id="basic_salary" required>
                                    <option value="" disabled selected>Select Salary Scale</option>
                                </select>
                            </div>
                            <!-- Hidden fields for Allowances -->
                            <input type="hidden" name="house_allowance_cluster2" id="house_allowance_cluster2">
                            <input type="hidden" name="medical_allowance" id="medical_allowance">
                            <input type="hidden" name="commuter_allowance" id="commuter_allowance">
                            <input type="hidden" name="nssf" id="nssf">
                            <input type="hidden" name="affordable_housing_levy" id="affordable_housing_levy">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" name="role" required>
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" name="register_employee" class="btn btn-primary">Register Employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#job_group').change(function () {
                var job_group_id = $(this).val();
                $.ajax({
                    url: 'salary_scale.php',
                    type: 'POST',
                    data: { job_group_id: job_group_id },
                    dataType: 'json',
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            $('#basic_salary').empty();
                            var starting_salary = parseFloat(data.starting_salary);
                            var yearly_increment = parseFloat(data.yearly_increment);
                            var max_salary = parseFloat(data.max_salary);

                            for (var salary = starting_salary; salary <= max_salary; salary += yearly_increment) {
                                $('#basic_salary').append('<option value="' + salary.toFixed(2) + '">' + salary.toFixed(2) + '</option>');
                            }

                            $('#house_allowance_cluster2').val(parseFloat(data.house_allowance_cluster2).toFixed(2));
                            $('#medical_allowance').val(parseFloat(data.medical_allowance).toFixed(2));
                            $('#commuter_allowance').val(parseFloat(data.commuter_allowance).toFixed(2));
                            $('#nssf').val(parseFloat(data.nssf).toFixed(2));

                            calculateAffordableHousingLevy();
                        }
                    }
                });
            });

            function calculateAffordableHousingLevy() {
                var salary = parseFloat($('#basic_salary').val()) || 0;
                var commuterAllowance = parseFloat($('#commuter_allowance').val()) || 0;
                var houseAllowanceCluster2 = parseFloat($('#house_allowance_cluster2').val()) || 0;
                var medicalAllowance = parseFloat($('#medical_allowance').val()) || 0;

                var levy = (salary + commuterAllowance + houseAllowanceCluster2 + medicalAllowance) * 0.015;
                $('#affordable_housing_levy').val(Math.round(levy));
            }
        });
    </script>
</body>
</html>
