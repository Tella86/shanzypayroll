<?php
session_start();
include('db.php');

// Ensure the user is logged in as admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch unread notifications
$unread_query = "SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5";
$unread_result = mysqli_query($conn, $unread_query);

// Fetch count of unread notifications
$unread_count_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0";
$unread_count_result = mysqli_fetch_assoc(mysqli_query($conn, $unread_count_query));
$unread_count = $unread_count_result['unread_count'];
?>

<!-- Bell Icon with Notification Dropdown in Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    
    <!-- Notification Bell Icon -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">notifications</i>
                <span id="unread-count" class="badge badge-danger"><?= $unread_count ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
                <h6 class="dropdown-header">Notifications</h6>
                
                <?php if (mysqli_num_rows($unread_result) > 0): ?>
                    <?php while ($notification = mysqli_fetch_assoc($unread_result)): ?>
                        <a class="dropdown-item" href="manage_leave_requests.php?notif_id=<?= $notification['id'] ?>" onclick="markAsRead(<?= $notification['id'] ?>)">
                            <?= $notification['message'] ?>
                            <small class="text-muted d-block"><?= date('d M Y h:i A', strtotime($notification['created_at'])) ?></small>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="dropdown-item">No new notifications</p>
                <?php endif; ?>
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" href="all_notifications.php">View All Notifications</a>
            </div>
        </li>
    </ul>
</nav>
<script>
function markAsRead(notification_id) {
    // Send an AJAX request to mark the notification as read
    $.ajax({
        url: 'mark_notification_read.php',
        method: 'POST',
        data: { notif_id: notification_id },
        success: function(response) {
            // Optionally reload the page or update the unread count dynamically
            $('#unread-count').text(response.unread_count);
        }
    });
}
</script>
