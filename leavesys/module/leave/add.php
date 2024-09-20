<?php 
  if (!isset($_SESSION['EMPID'])){
    redirect(web_root."/index.php");
   }

  $user = New User();
  $singleuser = $user->single_user($_SESSION['EMPID']);


 ?> 

 <div class="container">
  <div class="card card-register mx-auto mt-2">
    <div class="card-header">Add new Leave Application</div>
    <div class="card-body"> 

 <form action="controller.php?action=add" method="POST">
            <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="employid">Employee ID:</label>

                        <input name="deptid" type="hidden" value="<?php echo $singleuser->EMPID; ?>">
                         <input class="form-control input-sm" id="employid" name="EMPLOYID" placeholder=
                            "Employee ID" type="text" value="<?php echo $singleuser->EMPLOYID; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="name">Name:</label>

                        <input name="deptid" type="hidden" value="">
                         <input class="form-control input-sm" id="name" name="name" placeholder=
                            "Account Name" type="text" value="<?php echo $singleuser->EMPNAME; ?>" readonly>
                      </div>
                    </div>
                  </div>
<?PHP // `LEAVEID`, `EMPLOYID`, `DATESTART`, `DATEEND`, `NODAYS`, `SHIFTTIME`, `TYPEOFLEAVE`, `REASON`, `LEAVESTATUS`, `ADMINREMARKS`, `DATEPOSTED` ?>
                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="sex">Sex:</label>
                          <input class="form-control input-sm" id="sex" name="sex" placeholder=
                            "Account Name" type="text" value="<?php echo $singleuser->EMPSEX; ?>" readonly>  
                       
                      </div>
                    </div>
                  </div>
                   <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="sex">Available Leave:</label>
                          <input class="form-control input-sm" id="sex" name="sex" placeholder=
                            "Account Name" type="text" value="<?php echo $singleuser->AVELEAVE; ?>" readonly>  
                       
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="df">Date From:</label>
                          <input class="form-control input-sm" id="df" name="DATESTART"  type="Date" required>  
                       
                      </div>
                      <div class="col-md">
                      <label for="dt">Date To:</label>
                          <input class="form-control input-sm" id="dt" name="DATEEND" type="Date" required>  
                       
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-row">
                        
                    </div>
                  </div>
                   <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="Shift">Shift Time:</label>
                         <select class="form-control input-sm" name="SHIFTTIME" id="Shift">
                          <option value="All Day">All Day</option>
                          <option value="AM">AM</option>
                          <option value="PM">PM</option>
                         
                        </select> 
                       
                      </div>
                       <div class="col-md">
                      <label for="Leave">Type OF Leave:</label>
                     
                         <select class="form-control input-sm" name="TYPEOFLEAVE" id="Leave">
                         <?php 
             
                      global $mydb;
                      $mydb->setQuery("SELECT LEAVETYPE FROM `tblleavetype`");
                      $cur = $mydb->loadResultList();
                      $output .= '<option value="">Select Leave</option>';
                      foreach ($cur as $prov) {
                           $output .= '<option value="'. $prov->LEAVETYPE.'">'.$prov->LEAVETYPE.'</option>';
                        }
                        echo $output;
                      ?>
                        </select> 
                       
                      </div>
                    </div>
                  </div>
                   <div class="form-group">
                    <div class="form-row">
                       
                    </div>
                  </div>
                    <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="reason">Reason :</label>
                          <textarea class="form-control input-sm" name="REASON" id="reason"></textarea>  
                       
                      </div>
                    </div>
                  </div>
          
             
            <button class="btn btn-primary btn-block" name="save" type="submit" ><span class="glyphicon glyphicon-floppy-save"></span> Save Leave</button>

              
        </form>
             </div>
    </div>
  </div>
 