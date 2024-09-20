<?php  
      if (!isset($_SESSION['EMPID'])){
      redirect(web_root."admin/index.php");
     }


  @$USERID = $_GET['id'];
    if($USERID==''){
  redirect("index.php");
}
  $user = New User();
  $singleuser = $user->single_user($USERID);

?> 

<div class="container">
    <div class="card card-register mx-auto mt-2">
      <div class="card-header">Change Password</div>
      <div class="card-body"> 
 <form  action="controller.php?action=reset&id=<?php echo $_GET['id']; ?>&from=<?php if(isset($_GET['from'])) {   echo $_GET['from'];   }?>" method="POST">

                 <div
                  class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="employid">Employee ID:</label>

                        <input name="deptid" type="hidden" value="">
                         <input class="form-control input-sm" id="employid" name="employid" placeholder=
                            "Employee ID" type="text"  value="<?php echo $singleuser->EMPLOYID; ?>" readonly>
                      </div>
                    </div>
                  </div>
                   <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="name">Name:</label>
                        <input name="empid" type="hidden" value="<?php echo $singleuser->EMPID; ?>">
                         <input class="form-control input-sm" id="name" name="name" placeholder=
                            "Account Name" type="text" value="<?php echo $singleuser->EMPNAME; ?>" readonly>
                      </div>
                    </div>
                  </div>
                 <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="CURPASS">Current Password </label>

                        <input name="EMPID" type="hidden" value="<?php echo $singleuser->EMPID; ?>">
                         <input class="form-control input-sm" id="CURPASS" name="CURPASS" placeholder=
                            "Current Password" type="Password"  value="" required>
                      </div>
                    </div>
                  </div>
     
                   

                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="newpass">New Password </label>
                         <input class="form-control input-sm" id="newpass" name="newpass" placeholder=
                            "New Password" type="Password"  required>
                      </div>
                    </div>
                  </div>

                   <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="cpass">Confirm Password </label>
                         <input class="form-control input-sm" id="cpass" name="cpass" placeholder=
                            "Confirm Password" type="Password"  required>
                      </div>
                    </div>
                  </div>

              <button class="btn btn-primary btn-block" name="save" type="submit" ><span class="glyphicon glyphicon-floppy-save"></span> Reset Password</button>

   
          
        </form>
      

      </div>
    </div>
  </div>
 