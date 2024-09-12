<?php

session_start();
include('db.php'); // Database connection

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name']; // Store user's name in session
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: staff_dashboard.php');
        }
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid login credentials.</div>";
    }
}

?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Login to Employee Management</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="forgot_password.php" class="btn btn-link">Forgot Password?</a>
        </div>
    </div>
</body> -->
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .login-container {
            width: 320px;
            background-color: #2b2b2b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .top-icons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .top-icons i {
            color: #fff;
            background-color: #e51b38;
            padding: 10px;
            border-radius: 5px;
            font-size: 18px;
        }
        .login-container h2 {
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            margin-bottom: 20px;
        }
        .input-container {
            position: relative;
            margin-bottom: 15px;
        }
        .input-container i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #fff;
            background-color: #e51b38;
            padding: 10px;
            border-radius: 5px;
        }
        .input-container input {
            width: 100%;
            padding: 10px 15px 10px 45px;
            background-color: #f0f0f0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .input-container input:focus {
            outline: none;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #e51b38;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #b4162e;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="top-icons">
            <i class="fas fa-sign-in-alt"></i>
            <i class="fas fa-plus"></i>
            <i class="fas fa-key"></i>
            <a href="contact_us.html">
    <i class="fas fa-envelope"></i>
</a>

        </div>
        <h2>Staff/Admin Login</h2>
        <form action="" method="POST">
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="email" class="form-control" placeholder="email" name="email" required>

            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" placeholder="password" name="password" required>

            </div>
            <button class="login-btn" type="submit" name="login">Sign In</button>
        </form>
        <div class="text-center mt-3">
            <a href="forgot_password.php" class="btn btn-link">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
