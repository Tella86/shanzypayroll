<?php
session_start();
include('db.php'); // Include your database connection script

if (isset($_POST['login'])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        // Proceed with the login logic
    } else {
        echo "<div class='alert alert-danger'>Email and password are required.</div>";
    }
}

    // Prepare statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);  // "s" means the parameter is a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and store user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Redirect to respective dashboard based on role
            switch($user['role']) {
                case 'admin':
                    header('Location: admin_dashboard.php');
                    exit();
                case 'student':
                    header('Location: student_dashboard.php');
                    exit();
                case 'staff':
                    header('Location: staff_dashboard.php');
                    exit();
                case 'parent':
                    header('Location: parent_dashboard.php');
                    exit();
                default:
                    echo "Invalid user role!";
                    break;
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid password. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No user found with that email address.</div>";
    }

    $stmt->close();  // Close the statement

?>
