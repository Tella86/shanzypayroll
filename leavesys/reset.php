<?php
require_once("include/initialize.php");

 ?>
  <?php
 // login confirmation
  if(isset($_SESSION['EMPID'])){
    redirect(WEB_ROOT."index.php");
  }
  ?>
  

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin - Start Bootstrap Template</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>
<?php 

if(isset($_POST['btnreset'])){
  $email    = $_POST['user_email'];
  $idnumer  = $_POST['employid'];
  $newpass  = $_POST['newpass'];
  $cpass    = $_POST['cpass'];

   $user = new User();
    //make use of the static function, and we passed to parameters
    $res = $user->findCURemployee($email, $idnumer);
    if ($res == 1){
      if ($newpass == $cpass) {
        $emp = new User();
        $emp->PASSWRD  = sha1($newpass);
        $emp->updatemem($idnumer); 

         message("Password and Confirm Password  match!","success");
         redirect("login.php");
      }else{
        message("Password and Confirm Password does not match!","error");
         redirect("#");
      }
    }else{
        message("Username or Employee ID does not exist!","error");
         redirect("#");
       }
  
 } 
 ?> 
<body class="bg-dark">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">User Password Recovery</div>
      <div class="card-body">

          <?php check_message(); ?>
      <form action="" method="POST">
          <div class="form-group">
            <label for="exampleInputEmail1">Email address/Username</label>
            <input class="form-control" id="exampleInputEmail1" name="user_email" type="text" aria-describedby="emailHelp" placeholder="" required>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Employee ID Number</label>
            <input class="form-control" id="exampleInputPassword1" name="employid" type="text" placeholder="Employee ID Number" required>
          </div>
          <div class="form-group">
            <label for="newpass">New Password</label>
            <input class="form-control" id="newpass" name="newpass" type="Password"  required>
          </div>
          <div class="form-group">
            <label for="cpass">Confirm Password</label>
            <input class="form-control" id="cpass" name="cpass" type="Password"  required>
          </div>
          
        
           <button class="btn btn-warning btn-block" name="btnreset" >Reset Password </button>  
        </form>
       
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>


 


