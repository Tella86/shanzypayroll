
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<style>
  .icon-wrapper-alt {
            position: relative;
        }

        .user-icon {
            font-size: 30px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .fa-bell {
            font-size: 20px;
        }
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Icon -->
                <li class="nav-item dropdown">
                    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
                        <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                            <i class="fas fa-bell"></i>
                            <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-dark">
                        <a class="dropdown-item" href="#">View all notifications</a>
                    </div>
                </li>

                <!-- User Dropdown Menu -->
                <li class="nav-item dropdown current-user">
                    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
                        <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                            <i class="fas fa-user user-icon"></i>
                        </span> <br> Account&NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-dark">
                        <a class="dropdown-item" href="include/edit-profile.php">My Profile</a>
                        <a class="dropdown-item" href="../payroll/update_password.php">Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Log Out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>