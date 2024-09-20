<?php 
  if (!isset($_SESSION['EMPID'])){
    redirect(web_root."/index.php");
   }

  @$ID = $_GET['id'];
   $leave = New  Leavetype();
  $c = $leave->single_dept($ID);
 ?> 
  <div class="container">
    <div class="card card-register mx-auto mt-2">
      <div class="card-header">Update Leave Type</div>
      <div class="card-body">   
 <form action="controller.php?action=edit" method="POST">

                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="name">Leave Name:</label>
                      <input name="LEAVTID" type="hidden" value="<?php echo $c->LEAVTID; ?>">
                         <input class="form-control input-sm" id="name" name="name" placeholder=
                            "Leave Name" type="text"  value="<?php echo $c->LEAVETYPE; ?>" required>
                      </div>
                    </div>
                  </div>
                    <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="DESCRIPTION">Leave Description:</label>

                        
                         <input class="form-control input-sm" id="DESCRIPTION" name="DESCRIPTION" placeholder=
                            "Leave Description" type="text" value="<?php echo $c->DESCRIPTION; ?>" required>
                      </div>
                    </div>
                  </div>
                    
            <button class="btn btn-primary btn-block" name="save" type="submit" ><span class="glyphicon glyphicon-floppy-save"></span> Save Leave Type</button>



          
        </form>
                   
      </div>
    </div>
  </div>
 