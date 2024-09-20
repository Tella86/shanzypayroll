<?php  
      if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }
$dft = $_GET['id'];
$Leave = new Leave();
$SL = $Leave->single_leave($dft);

?> 

 <div class="container">
  <div class="card card-register mx-auto mt-2">
    <div class="card-header">Add new Leave Application</div>
    <div class="card-body"> 

 <form action="controller.php?action=edit" method="POST">
            <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="employid">Employee ID:</label>

                        <input name="LEAVEID" type="hidden" value="<?php echo $SL->LEAVEID; ?>">
                         <input class="form-control input-sm" id="employid" name="EMPLOYID" placeholder=
                            "Employee ID" type="text" value="<?php echo $SL->EMPLOYID; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <?PHP // `LEAVEID`, `EMPLOYID`, `DATESTART`, `DATEEND`, `NODAYS`, `SHIFTTIME`, `TYPEOFLEAVE`, `REASON`, `LEAVESTATUS`, `ADMINREMARKS`, `DATEPOSTED` 
               $user = New User();
              $singleuser = $user->single_emp($SL->EMPLOYID);

            $APLYL = $SL->NODAYS;
            $AVL   = $singleuser->AVELEAVE;
            $REMLEAVE = $AVL - $APLYL;

            ?>
                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="name">Name:</label>
                           <input class="form-control input-sm" id="name" name="name" placeholder=
                            "Account Name" type="text" value="<?php echo $singleuser->EMPNAME; ?>" readonly>
                      </div>
                    </div>
                  </div>

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
                      <label for="NODAYS">Applied No. of Days:</label>
                      <input name="REMLEAVE" type="hidden" value="<?php echo $REMLEAVE; ?>">
                      <input name="EMPID" type="hidden" value="<?php echo $singleuser->EMPID; ?>">
                          <input class="form-control input-sm" id="NODAYS" name="NODAYS"  type="text" value="<?php echo $SL->NODAYS; ?>" readonly>  
                       
                      </div>
                      <div class="col-md">
                      <label for="AVELEAVE">Available No. of Leave :</label>
                          <input class="form-control input-sm" id="AVELEAVE" name="AVELEAVE" type="text" value="<?php echo $singleuser->AVELEAVE; ?>" readonly>  
                       
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="df">Date From:</label>
                          <input class="form-control input-sm" id="df" name="DATESTART"  type="Date" value="<?php echo $SL->DATESTART; ?>" readonly>  
                       
                      </div>
                      <div class="col-md">
                      <label for="dt">Date To:</label>
                          <input class="form-control input-sm" id="dt" name="DATEEND" type="Date" value="<?php echo $SL->DATESTART; ?>" readonly>  
                       
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
                         <select class="form-control input-sm" name="SHIFTTIME" id="Shift" readonly>
                          <?php if ($SL->SHIFTTIME == 'All Day') {
                          echo '  <option value="All Day" selected>All Day</option>
                                  <option value="AM">AM</option>
                                  <option value="PM">PM</option>
                          ';
                          }elseif ($SL->SHIFTTIME == 'AM') {
                             echo '  <option value="All Day" >All Day</option>
                                  <option value="AM" selected>AM</option>
                                  <option value="PM">PM</option>
                          ';
                          } elseif ($SL->SHIFTTIME == 'PM') {
                           echo '  <option value="All Day" >All Day</option>
                                  <option value="AM">AM</option>
                                  <option value="PM" selected>PM</option>
                          ';
                          }
                           ?>
                       
                                                  
                        </select> 
                       
                      </div>
                       <div class="col-md">
                      <label for="Leave">Type OF Leave:</label>
                     
                         <select class="form-control input-sm" name="TYPEOFLEAVE" id="Leave" readonly>

                           <option value="<?php echo $SL->TYPEOFLEAVE; ?>"><?php echo $SL->TYPEOFLEAVE; ?></option>
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
                        <div class="col-md">
                      <label for="reason">Reason :</label>
                          <textarea class="form-control input-sm" name="REASON" id="reason" readonly><?php echo $SL->REASON; ?></textarea>  
                       
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="ADMINREMARKS">Remarks :</label>
                          <textarea class="form-control input-sm" name="ADMINREMARKS" id="ADMINREMARKS" ><?php echo $SL->ADMINREMARKS; ?></textarea>  
                       
                      </div>
                    </div>
                  </div>
                   <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                       <label for="LEAVESTATUS">Leave Status:</label>
                         <select class="form-control input-sm" name="LEAVESTATUS" id="LEAVESTATUS" required>
                           
                          <?php 
                              if ($SL->LEAVESTATUS == 'PENDING') {
                               echo '  <option value="PENDING" selected>PENDING</option>
                                       <option value="APPROVED">APPROVED</option>
                                       <option value="REJECTED">REJECTED</option>';
                              }elseif ($SL->LEAVESTATUS == 'APPROVED') {
                              echo '  <option value="PENDING" >PENDING</option>
                                       <option value="APPROVED" selected>APPROVED</option>
                                       <option value="REJECTED">REJECTED</option>';
                              }elseif ($SL->LEAVESTATUS == 'REJECTED') {
                               echo '  <option value="PENDING" >PENDING</option>
                                       <option value="APPROVED">APPROVED</option>
                                       <option value="REJECTED" selected>REJECTED</option>';
                              }



                          ?>
                         
                            
                                                  
                        </select> 
                      </div>
                    </div>
                  </div>
          
             
            <button class="btn btn-primary btn-block" name="save" type="submit" ><span class="glyphicon glyphicon-floppy-save"></span> Save Leave</button>

              
        </form>
             </div>
    </div>
  </div>
 