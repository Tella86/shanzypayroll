<?php
session_start();
include('db.php');

// Ensure the user is logged in as admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Check if notification ID is passed
if (isset($_POST['notif_id'])) {
    $notif_id = $_POST['notif_id'];

    // Mark the notification as read
    $update_query = "UPDATE notifications SET is_read = 1 WHERE id = $notif_id";
    mysqli_query($conn, $update_query);

    // Fetch updated unread count
    $unread_count_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0";
    $unread_count_result = mysqli_fetch_assoc(mysqli_query($conn, $unread_count_query));

    // Return the unread count as JSON
    echo json_encode(['unread_count' => $unread_count_result['unread_count']]);
}
?>
