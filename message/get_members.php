<?php
require 'db.php'; // Ensure this file sets up your PDO connection

// Get the group from query parameter
$group = isset($_GET['group']) ? $_GET['group'] : '';

try {
    switch ($group) {
        case 'employees':
            $query = "SELECT name, phone FROM employees";
            break;
        case 'teachers':
            $query = "SELECT name, phone FROM teachers";
            break;
        case 'students':
            $query = "SELECT name, phone FROM students";
            break;
        case 'board':
            $query = "SELECT name, phone FROM board_members";
            break;
        default:
            echo json_encode([]);
            exit;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($members);
} catch (Exception $e) {
    echo json_encode([]);
}
?>