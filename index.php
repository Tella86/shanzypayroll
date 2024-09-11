<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College ERP System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 56px;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5em;
        }
        .container {
            margin-top: 50px;
        }
        footer {
            margin-top: 50px;
            padding: 20px;
            background-color: #f8f9fa;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">College ERP</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <!-- Navigation for different user roles -->
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manage_students.php">Manage Students</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manage_staff.php">Manage Staff</a>
                            </li>
                        <?php elseif ($_SESSION['role'] == 'student'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="student_dashboard.php">Student Dashboard</a>
                            </li>
                        <?php elseif ($_SESSION['role'] == 'staff'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="staff_dashboard.php">Staff Dashboard</a>
                            </li>
                        <?php elseif ($_SESSION['role'] == 'parent'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="parent_dashboard.php">Parent Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.html">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to College ERP</h1>
            <p class="lead">A comprehensive ERP solution for managing college operations like student records, attendance, marks, transport, and more!</p>
            <hr class="my-4">
            <?php if (!isset($_SESSION['role'])): ?>
                <p>Login to your account to access your dashboard.</p>
                <a class="btn btn-primary btn-lg" href="login.html" role="button">Login</a>
            <?php else: ?>
                <p>Access your dashboard to manage your tasks.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="text-muted">&copy; 2024 College ERP. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
