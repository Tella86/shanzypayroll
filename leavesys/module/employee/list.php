<?php
	 if (!isset($_SESSION['EMPID'])){
      redirect(web_root."admin/index.php");
     }

?>
<div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-table"></i> List of Employee   <a href="index.php?view=add" class="btn btn-primary  ">  <i class="fa fa-plus-circle fw-fa"></i> New</a></div>

         
        <div class="card-body">
          <div class="table-responsive">
	 		<form action="controller.php?action=delete" Method="POST">  
			   		
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				
				  <thead>
				  	<tr>
				  		<th>EMP.ID</th>
				  		<th>Employee Name</th>
				  		<th>Sex</th>
				  		<th>Email/Username</th>
				  		<th>Company</th>
				  		<th>Department</th>
				  		<th>Position</th>
				  		<th>Ave. Leave</th>
				  		
				  		<th width="20%" >Action</th>
				 
				  	</tr>	
				  </thead> 
				  <tbody>
				  
				  	<?php 
				  		// `EMPID`, `EMPNAME`, `EMPADDRESS`, `EMPCONTACT`, `EMPPOSITION`, `USERNAME`, `PASSWRD`, `ACCSTATUS`
				  		$mydb->setQuery("SELECT * 
											FROM  `tblemployee`");
				  		$cur = $mydb->loadResultList();

						foreach ($cur as $result) {
				  		echo '<tr>';
				  		// echo '<td width="5%" align="center"></td>';
				  		echo '<td>' . $result->EMPLOYID.'</a></td>';
				  		echo '<td>' . $result->EMPNAME.'</a></td>';
				  		echo '<td>' . $result->EMPSEX.'</a></td>';
				  		echo '<td>'. $result->USERNAME.'</td>';
				  		echo '<td>'. $result->COMPANY.'</td>';
				  		echo '<td>'. $result->DEPARTMENT.'</td>';
				  		echo '<td>'. $result->EMPPOSITION.'</td>';
				  		echo '<td>'. $result->AVELEAVE.'</td>';
				  		/*echo '<td>'. $result->ACCSTATUS.'</td>';*/
				  		If($result->EMPID==$_SESSION['EMPID'] || $result->EMPPOSITION=='Administrator' || $result->EMPPOSITION=='Administrator') {
				  			$active = "Disabled";

				  		}else{
				  			$active = "";

				  		}

				  		echo '<td align="center" > 
				  		<a title="Edit" href="index.php?view=edit&id='.$result->EMPID.'"  class="btn btn-primary btn-sm  ">  <span class="fa fa-edit fw-fa"></span></a>
				  		<a title="Change Password" href="index.php?view=reset&id='.$result->EMPID.'&from=emp"  class="btn btn-info btn-sm  ">  <span class="fa fa-edit fw-fa"></span></a>
				  				<a title="Delete" href="controller.php?action=delete&id='.$result->EMPID.'" class="btn btn-danger btn-sm  delete" ><span class="fa fa-trash-o fw-fa"></span></a>		 
				  					 </td>';
				  		echo '</tr>';
				  	} 
				  	?>
				  </tbody>
					
				</table>
 

				</form>
       </div>
        </div>
      
      </div>