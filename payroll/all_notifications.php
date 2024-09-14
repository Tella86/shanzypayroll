<?php
session_start();
include('db.php'); // Include database connection

// Ensure the user is logged in
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Pagination setup
$limit = 10; // Number of notifications to display per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch all notifications (both read and unread)
$notifications_query = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT $start, $limit";
$notifications_result = mysqli_query($conn, $notifications_query);

// Fetch total number of notifications for pagination
$count_query = "SELECT COUNT(id) AS total_notifications FROM notifications";
$count_result = mysqli_fetch_assoc(mysqli_query($conn, $count_query));
$total_notifications = $count_result['total_notifications'];
$total_pages = ceil($total_notifications / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Notifications</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add some custom styles for links */
        .timeline-content a {
            color: inherit;
            text-decoration: none;
        }

        .timeline-content a:hover {
            color: #007bff;
            text-decoration: underline;
        }
    </style>
<?php include '../leave/header.php';?>

<div class="container mt-5">
    <h2>All Notifications</h2>
    <div class="timeline timeline-one-side">
        <?php if (mysqli_num_rows($notifications_result) > 0): ?>
            <?php while ($notification = mysqli_fetch_assoc($notifications_result)): ?>
                <div class="timeline-block mb-3">
                    <span class="timeline-step">
                        <i class="material-icons text-<?= $notification['is_read'] ? 'info' : 'warning' ?> text-gradient">notifications</i>
                    </span>
                    <div class="timeline-content">
                        <!-- Make notification clickable -->
                        <a href="manage_leave_requests.php?notif_id=<?= $notification['id'] ?>">
                            <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $notification['message'] ?></h6>
                        </a>
                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                            <?= date('d M Y h:i A', strtotime($notification['created_at'])) ?>
                            <?= $notification['is_read'] ? '<span class="badge badge-info ml-2">Read</span>' : '<span class="badge badge-warning ml-2">Unread</span>' ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No notifications available.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="all_notifications.php?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="all_notifications.php?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="all_notifications.php?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
