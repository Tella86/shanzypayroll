<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Fetch the logged-in user's details
$user_id = $_SESSION['user_id'];
$query = "SELECT e.name, d.name AS department_name, j.job_group AS job_group_name 
          FROM employees e 
          JOIN departments d ON e.department_id = d.id 
          JOIN jobgroup j ON e.job_group_id = j.id 
          WHERE e.id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $employee = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user details.";
}

// Fetch the total number of employees
$employeeCountQuery = "SELECT COUNT(*) AS total_employees FROM employees";
$employeeCountResult = mysqli_query($conn, $employeeCountQuery);
$employeeCount = mysqli_fetch_assoc($employeeCountResult)['total_employees'];

// Fetch the total gross pay, net pay, and total deductions
$payrollQuery = "SELECT SUM(gross_salary) AS total_gross_pay, SUM(net_salary) AS total_net_pay, SUM(deductions) AS total_deductions 
                 FROM payroll 
                 WHERE MONTH(processed_date) = MONTH(CURRENT_DATE()) AND YEAR(processed_date) = YEAR(CURRENT_DATE())";
$payrollResult = mysqli_query($conn, $payrollQuery);
$payrollData = mysqli_fetch_assoc($payrollResult);

$totalGrossPay = $payrollData['total_gross_pay'] ?? 0;
$totalNetPay = $payrollData['total_net_pay'] ?? 0;
$totalDeductions = $payrollData['total_deductions'] ?? 0;
$role = $_SESSION['role'];

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$searchQuery = "SELECT e.name, d.name AS department_name, j.job_group AS job_group_name 
                FROM employees e 
                JOIN departments d ON e.department_id = d.id 
                JOIN jobgroup j ON e.job_group_id = j.id 
                WHERE e.name LIKE '%$search%' OR d.name LIKE '%$search%'";

$searchResult = mysqli_query($conn, $searchQuery);

// // Redirect to clear the search query from the URL after displaying the results
// if (!empty($search)) {
//     // If search was performed, redirect after loading the page
//     header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
       
    </style>
</head>

<body>
<?php include 'include/header.php';?>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
            <h2>Welcome, <?php echo htmlspecialchars($employee['name']); ?>!</h2>
            </div>
            <ul class="components">
                <li><a href="javascript:void(0);" id="manageEmployeesLink"><i class="fas fa-users"></i> Manage Employees</a></li>
                <li><a href="javascript:void(0);" id="processPayrollLink"><i class="fas fa-money-check-alt"></i> Process Payroll</a></li>
                <li><a href="javascript:void(0);" id="viewproceedpayrollLink"><i class="fas fa-money-check-alt"></i> View Payroll</a></li>
                <li><a href="javascript:void(0);" id="viewdeductionslink"><i class="fas fa-money-check-alt"></i> View Deductions</a></li>
                <li><a href="javascript:void(0);" id="auditLogsLink"><i class="fas fa-file-alt"></i> Audit Logs</a></li>
                <li><a href="javascript:void(0);" id="viewEmployeesLink"><i class="fas fa-users"></i> View Employees</a></li>
                <li><a href="javascript:void(0);" id="updatepasswordLink"><i class="fas fa-key"></i>update Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <!-- Page Content -->
        <div id="content">
            <button type="button" id="sidebarCollapse" class="btn toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <!-- Search Form -->
             <!-- Search Form -->
             <!-- <div class="search-bar">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search Employee by Name or Department" class="form-control" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>
            </div> -->
            <!-- Welcome message -->
            <div id="content-area">
                <p>You are logged in as <strong><?php echo htmlspecialchars($role); ?></strong>.</p>
                <!-- <p>This is your admin dashboard where you can manage employees, process payroll, and view audit logs.</p> -->
            </div>
              <!-- Display Search Results only if a search is made -->
            <?php if (!empty($search)): ?>
                <?php if ($searchResult && mysqli_num_rows($searchResult) > 0): ?>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Job Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($searchResult)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['job_group_name']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No employees found matching '<?php echo htmlspecialchars($search); ?>'.</p>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row">
    <div class="col-md-3">
        <div class="card bg-light mb-3">
            <div class="card-header" style="background-color: #4cc9f0;"><a href="view_employees.php" style=" color: white;">Total Employees</a></div>
            <div class="card-body"   style="background-color: #f2e8cf">
                <h5 class="card-title"><?php echo $employeeCount; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light mb-3">
            <div class="card-header" style="background-color: #4cc9f0">Total Gross Pay</div>
            <div class="card-body" style="background-color: #f2e8cf">
                <h5 class="card-title"><?php echo number_format($totalGrossPay, 2); ?> KSH</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light mb-3">
            <div class="card-header" style="background-color: #4cc9f0">Total Net Pay</div>
            <div class="card-body" style="background-color: #f2e8cf">
                <h5 class="card-title"><?php echo number_format($totalNetPay, 2); ?> KSH</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light mb-3">
            <div class="card-header" style="background-color: #4cc9f0">Total Deductions</div>
            <div class="card-body" style="background-color: #f2e8cf">
                <h5 class="card-title"><?php echo number_format($totalDeductions, 2); ?> KSH</h5>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
   <br><br>
<!-- Settings icon for theme color change -->
<!-- Settings icon for theme color change -->
<i class="fas fa-cog settings-icon" id="settingsIcon"></i>

<!-- Theme selector -->
<div class="theme-selector" id="themeSelector"></div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- AJAX to load different pages in the admin dashboard -->
    <script>
       $(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    // Function to load content and hide the previous content
    function loadContent(pageUrl) {
        $('#content-area').fadeOut(200, function () {  // First, fade out the current content
            $('#content-area').load(pageUrl, function() {
                $('#content-area').fadeIn(200);  // Then, load the new content and fade it in
            });
        });
        
        // Hide the row with the cards
        $('.row').hide();
    }

    // Bind click events to the sidebar links
    $('#manageEmployeesLink').on('click', function () {
        loadContent('manage_employees.php');
    });

    $('#processPayrollLink').on('click', function () {
        loadContent('process_payroll.php');
    });

    $('#auditLogsLink').on('click', function () {
        loadContent('audit_logs.php');
    });

    $('#viewEmployeesLink').on('click', function () {
        loadContent('view_employees.php');
    });

    $('#viewproceedpayrollLink').on('click', function () {
        loadContent('view_proceed_payroll.php');
    });

    $('#viewdeductionslink').on('click', function () {
        loadContent('view_deductions.php');
    });

    $('#updatepasswordLink').on('click', function () {
        loadContent('update_password.php');
    });

    // Show/Hide theme selector on settings icon click
    $('#settingsIcon').on('click', function () {
        $('#themeSelector').toggle();
    });

    // Change theme color
    $('.theme-option').on('click', function () {
        var color = $(this).data('color');
        $('nav').css('background-color', color);
    });
});
 // Toggle the visibility of the theme selector when the settings icon is clicked
 $('#settingsIcon').on('click', function () {
            $('#themeSelector').toggle(); // Show or hide the theme selector
        });

        // Change theme color on clicking a theme option
        $(document).on('click', '.theme-option', function () {
            var color = $(this).data('color');
            $('nav').css('background-color', color);
        });

        window.onload = function () {
            // Fetch the saved user theme from the backend
            fetch('/get-theme.php')
                .then(response => response.json())
                .then(data => {
                    if (data.themeColor) {
                        document.body.style.backgroundColor = data.themeColor; // Apply saved theme
                    }
                })
                .catch(error => {
                    console.error('Error fetching saved theme:', error);
                });

            // Fetch available themes from the backend
            fetch('/fetch-themes.php')
                .then(response => response.json())
                .then(themes => {
                    const themeSelector = document.getElementById('themeSelector');

                    themes.forEach(theme => {
                        // Create a div for each theme option
                        const themeOption = document.createElement('div');
                        themeOption.classList.add('theme-option');
                        themeOption.setAttribute('data-color', theme.background_color);
                        themeOption.style.backgroundColor = theme.background_color;
                        themeOption.style.color = theme.text_color;

                        // Append to theme selector
                        themeSelector.appendChild(themeOption);

                        // Add click event to apply theme and save it
                        themeOption.addEventListener('click', function () {
                            const selectedColor = theme.background_color;
                            document.body.style.backgroundColor = selectedColor;

                            // Save the selected theme to the backend
                            fetch('/save-theme.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ themeColor: selectedColor }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Theme saved:', data);
                            })
                            .catch(error => {
                                console.error('Error saving theme:', error);
                            });
                        });
                    });
                })
                .catch(error => {
                    console.error('Error fetching themes:', error);
                });
        };
    </script>

</body>
</html>
