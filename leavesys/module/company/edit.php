<?php  
      if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }

  @$cID = $_GET['id'];
   $Company = New  Company();
  $c = $Company->single_Company($cID);

?> 
<div class="container">
    <div class="card card-register mx-auto mt-2">
      <div class="card-header">Update Company</div>
      <div class="card-body"> 
 <form  action="controller.php?action=edit" method="POST">

                 
            <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="name">Company Name:</label>
                        <input name="COMPID" type="hidden" value="<?php echo $c->COMPID; ?>">
                         <input class="form-control input-sm" id="name" name="name" placeholder=
                            "COMPANY Name" type="text" value="<?php echo $c->COMPANY; ?>" required>
                      </div>
                    </div>
                  </div>
                    
            <button class="btn btn-primary btn-block" name="save" type="submit" ><span class="glyphicon glyphicon-floppy-save"></span> Save Company</button>

   
          
        </form>
      

      </div>
    </div>
  </div>
 