<?php
include('db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiration > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (isset($_POST['submit'])) {
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $new_password, $token);
            $stmt->execute();

            echo "<div class='alert alert-success'>Your password has been successfully reset. You can now <a href='login.php'>login</a>.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid or expired token.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>No token provided.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Reset Password</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
