<?php

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">



<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="admin_dashboard.php">Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>
   
             <!-- Search Form -->
<div class="search-bar">
    <form method="GET" action="" class="d-flex">
        <input type="text" name="search" placeholder="Search Employee by Name or Department" class="form-control me-2" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ml-auto">
          <!-- Notifications Icon -->
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                      <i class="fas fa-bell" style="fa-bel; font-size: 20px; "></i>
                      <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
                  </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
                  <a class="dropdown-item" href="#">View all notifications</a>
              </div>
          </li>

          <!-- User Dropdown Menu -->
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                      <i class="fas fa-user user-icon" style="user-icon; font-size: 30px;  width: 30px; height: 30px; border-radius: 50%;"></i>
                  </span>
                  Account
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="include/edit-profile.php">My Profile</a>
                  <a class="dropdown-item" href="../payroll/update_password.php">Change Password</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="logout.php">Log Out</a>
              </div>
          </li>
      </ul>
  </div>
</nav>
