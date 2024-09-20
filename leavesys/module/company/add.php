<?php 
  if (!isset($_SESSION['EMPID'])){
    redirect(web_root."/index.php");
   }


 ?> 
  <div class="container">
    <div class="card card-register mx-auto mt-2">
      <div class="card-header">Add new Company</div>
      <div class="card-body">   
 <form action="controller.php?action=add" method="POST">

                  <div class="form-group">
                    <div class="form-row">
                        <div class="col-md">
                      <label for="name">Company Name:</label>

                        <input name="deptid" type="hidden" value="">
                         <input class="form-control input-sm" id="name" name="name" placeholder=
                            "Account Name" type="text" required>
                      </div>
                    </div>
                  </div>
                
            <button class="btn btn-primary btn-block" name="save" type="submit" ><span class="glyphicon glyphicon-floppy-save"></span> Save Company</button>



          
        </form>
                   
      </div>
    </div>
  </div>
 