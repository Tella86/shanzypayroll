<?php
include 'db.php';

$id = $_GET['id'];

$query = "DELETE FROM employees WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Employee deleted successfully!";
} else {
    echo "Error: " . $stmt->error;
}
?>
