<?php 
require_once 'include/initialize.php';
// Four steps to closing a session
// (i.e. logging out)

// 1. Find the session
@session_start();


unset( $_SESSION['EMPID'] );
unset( $_SESSION['EMPNAME'] );
unset( $_SESSION['USERNAME'] );
unset( $_SESSION['PASSWRD'] );
unset( $_SESSION['ACCSTATUS'] );
unset( $_SESSION['EMPPOSITION'] );
// 4. Destroy the session
// session_destroy();
redirect(WEB_ROOT."login.php?logout=1");

?>