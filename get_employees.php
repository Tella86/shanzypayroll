<?php
require 'db.php';

try {
    $query = "SELECT name, phone FROM employees";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($employees);
} catch (Exception $e) {
    echo json_encode([]);
}
?>
