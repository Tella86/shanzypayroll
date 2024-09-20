<?php
require 'db.php'; // Ensure this file sets up your PDO connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $group = $_POST['group'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    try {
        switch ($group) {
            case 'employees':
                $query = "INSERT INTO employees (name, phone) VALUES (:name, :phone)";
                break;
            case 'teachers':
                $query = "INSERT INTO teachers (name, phone) VALUES (:name, :phone)";
                break;
            case 'students':
                $query = "INSERT INTO students (name, phone) VALUES (:name, :phone)";
                break;
            case 'board':
                $query = "INSERT INTO board_members (name, phone) VALUES (:name, :phone)";
                break;
            default:
                throw new Exception("Invalid group selected.");
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        echo "<div class='alert alert-success'>Member added successfully!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="text-center">Add Member</h2>
    <form id="addMemberForm" action="add_member.php" method="POST">
        <div class="form-group">
            <label for="group">Select Group</label>
            <select id="group" name="group" class="form-control" required>
                <option value="">-- Select Group --</option>
                <option value="employees">Employees</option>
                <option value="teachers">Teachers</option>
                <option value="students">Students</option>
                <option value="board">Board Members</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number (e.g. +1234567890)" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Member</button>
    </form>

    <div id="result" class="mt-4"></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>
