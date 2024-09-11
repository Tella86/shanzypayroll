<?php
session_start();

if ($_SESSION['role'] != 'staff') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Lato", sans-serif;
        }
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }
        #sidebar.active {
            margin-left: -250px;
        }
        #sidebar ul.components {
            padding: 20px;
        }
        #sidebar ul li {
            padding: 10px;
            font-size: 1.2em;
        }
        #sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }
        #sidebar ul li a:hover {
            background: #007bff;
        }
        #content {
            width: 100%;
            padding: 20px;
        }
        .toggle-btn {
            background: #343a40;
            color: #fff;
            border: none;
            margin: 10px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Staff Dashboard</h3>
        </div>
        <ul class="components">
            <li>
                <a href="javascript:void(0);" id="viewHome"><i class="fas fa-home"></i> Home</a>
            </li>
            <li>
                <a href="javascript:void(0);" id="viewPayroll"><i class="fas fa-file-invoice-dollar"></i> View Payroll</a>
            </li>
            <li><a href="javascript:void(0);" id="updatepasswordLink"><i class="fas fa-key"></i>update Password</a></li>

            <li>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>

        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <button type="button" id="sidebarCollapse" class="btn toggle-btn">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- This is where the content will be loaded -->
        <div id="content-area">
            <h2>Welcome, Staff</h2>
            <!-- <p>This is your staff dashboard where you can view your payroll details.</p> -->
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- AJAX to load view_payroll.php -->
<script>
    $(document).ready(function () {
        // Toggle the sidebar
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        // Load view_payroll.php when clicking "View Payroll"
        $('#viewPayroll').on('click', function() {
            $('#content-area').load('view_payroll.php');
        });

        // Load home content
        $('#viewHome').on('click', function() {
            $('#content-area').html('<h2>Welcome, Staff</h2><p>This is your staff dashboard where you can view your payroll details.</p>');
        });
        $('#updatepasswordLink').on('click', function () {
                $('#content-area').load('update_password.php');
            });
    });
</script>

</body>
</html>
