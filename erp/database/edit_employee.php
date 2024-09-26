<?php
include 'db.php';

$id = $_GET['id'];
$query = "SELECT employees.id, users.name, users.email, employees.position, employees.salary, employees.hire_date 
          FROM employees 
          JOIN users ON employees.user_id = users.id 
          WHERE employees.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (isset($_POST['submit'])) {
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];

    $query = "UPDATE employees SET position=?, salary=?, hire_date=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsi", $position, $salary, $hire_date, $id);
    
    if ($stmt->execute()) {
        echo "Employee updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<form method="post" action="edit_employee.php?id=<?php echo $id; ?>">
    <label>Position: </label>
    <input type="text" name="position" value="<?php echo $row['position']; ?>" required><br>
    <label>Salary: </label>
    <input type="number" name="salary" value="<?php echo $row['salary']; ?>" required><br>
    <label>Hire Date: </label>
    <input type="date" name="hire_date" value="<?php echo $row['hire_date']; ?>" required><br>
    <input type="submit" name="submit" value="Update Employee">
</form>
