<?php  
      if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }

  @$ID = $_GET['id'];
   $dept = New  Department();
  $c = $dept->single_dept($ID);

?> 
<div class="container">
    <div class="card card-register mx-auto mt-2">
      <div class="card-header">Update Department</div>
      <div class="card-body"> 
 <form  action="controller.php?action=edit" method="POST">

                 
            <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="name">Department Name:</label>
                        <input name="DEPTID" type="hidden" value="<?php echo $c->DEPTID; ?>">
                         <input class="form-control input-sm" id="name" name="name" placeholder=
                            "COMPANY Name" type="text" value="<?php echo $c->DEPTNAME; ?>" required>
                      </div>
                    </div>
                  </div>
                    <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="deptshrtname">Department Short Name:</label>
                         <input class="form-control input-sm" id="deptshrtname" name="deptshrtname" placeholder=
                           "Department Short Name" type="text" value="<?php echo $c->DEPTSHORTNAME; ?>" required>
                      </div>
                    </div>
                  </div>
                    
            <button class="btn btn-primary btn-block" name="save" type="submit" ><span class="glyphicon glyphicon-floppy-save"></span> Save Department</button>

   
          
        </form>
      

      </div>
    </div>
  </div>
 