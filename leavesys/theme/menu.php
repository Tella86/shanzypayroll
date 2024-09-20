<ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="<?php echo WEB_ROOT; ?>index.php">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li>
       
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Profile">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-user"></i>
            <span class="nav-link-text">Profile</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents">
            <li>
              <a  href="<?php echo WEB_ROOT; ?>module/employee/index.php?view=edit&id=<?php echo $_SESSION['EMPID']; ?>">View</a>
            </li>
            <li>
              <a href="<?php echo WEB_ROOT; ?>module/employee/index.php?view=reset&id=<?php echo $_SESSION['EMPID']; ?>">Reset Password</a>
            </li>
          </ul>
        </li>

        <?php
          if ($_SESSION['EMPPOSITION'] == 'Normal user') {
          
          echo '  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Leave">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponentsL" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-cubes"></i>
            <span class="nav-link-text">Leave</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponentsL">
            <li>
              <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave/index.php?view=add">
              <?php 
          echo  'Apply Leave</a>
            </li>
            <li>
                <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave">
                 <?php 
          echo  'Manage Leave</a>
            </li>
          </ul>
        </li>';
          }elseif ($_SESSION['EMPPOSITION'] == 'Supervisor user' || $_SESSION['EMPPOSITION'] == 'Manager user') {
          
         echo  '
         <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Leave">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponentsL" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-cubes"></i>
            <span class="nav-link-text">Leave</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponentsL">
            <li>
              <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave/index.php?view=add">
               <?php 
          echo  'Apply Leave</a>
            </li>
            <li>
                <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave/">
                 <?php 
          echo  'Manage Leave</a>
            </li>
            
          
          </ul>
        </li>';
          
        }else{
          echo '
             <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Company">
          <a class="nav-link" href="'; ?> <?php echo WEB_ROOT; ?>module/Company/">
             <?php 
          echo  '
            <i class="fa fa-university"></i>
            <span class="nav-link-text">Company</span>
          </a>
        </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employee">
          <a class="nav-link" href="'; ?><?php echo WEB_ROOT; ?>module/department/"> 
            <?php 
          echo  '
            <i class="fa fa-fw fa-archive"></i>
            <span class="nav-link-text">Department</span>
          </a>
        </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employee">
          <a class="nav-link" href="'; ?><?php echo WEB_ROOT; ?>module/leavetype/">
             <?php 
          echo  '
            <i class="fa fa-fw fa-cube"></i>
            <span class="nav-link-text">Leave Type</span>
          </a>
        </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employee">
          <a class="nav-link" href="'; ?><?php echo WEB_ROOT; ?>module/employee/">
             <?php 
          echo  '
            <i class="fa fa-fw fa-users"></i>
            <span class="nav-link-text">Employee</span>
          </a>
        </li>
         <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Leave">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponentsL" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-cubes"></i>
            <span class="nav-link-text">Leave</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponentsL">
            <li>
              <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave/index.php?view=add">
               <?php 
          echo  'Apply Leave</a>
            </li>
            <li>
                <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave/">
                 <?php 
          echo  'Manage Leave</a>
            </li>
             <li>
                <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave/index.php?view=approved">
                 <?php 
          echo  'Approved Leave</a>
            </li>
            <li>
                <a  href="'; ?><?php echo WEB_ROOT; ?>module/leave/index.php?view=rejected">
                 <?php 
          echo  'Rejected Leave</a>
            </li>
          
          </ul>
        </li>';

          
          }


        ?>

        

        
          
       
      </ul>