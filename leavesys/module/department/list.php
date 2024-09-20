<?php
	 if (!isset($_SESSION['EMPID'])){
      redirect(web_root."admin/index.php");
     }

?>
<div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-archive"></i> List of Department   <a href="index.php?view=add" class="btn btn-primary btn-sm  ">  <i class="fa fa-plus-circle fw-fa"></i> New</a></div>

         
        <div class="card-body">
          <div class="table-responsive">
	 		<form action="controller.php?action=delete" Method="POST">  
			   		
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				
				  <thead>
				  	<tr>
				  		
				  		<th>DEPT. ID</th>
				  		<th>Department Name</th>
				  		<th>Depart Short Name</th>
				  		<th width="15%" >Action</th>
				 
				  	</tr>	
				  </thead> 
				  <tbody>
				  
				  	<?php 
				  		////`DEPTID`, `DEPTNAME`, `DEPTSHORTNAME`, `DATECREATED`
				  		$mydb->setQuery("SELECT * 
											FROM  `tbldepts`");
				  		$cur = $mydb->loadResultList();

						foreach ($cur as $result) {
				  		echo '<tr>';
				  		// echo '<td width="5%" align="center"></td>';
				  		echo '<td>' . $result->DEPTID.'</a></td>';
				  		echo '<td>' . $result->DEPTNAME.'</a></td>';
				  		echo '<td>' . $result->DEPTSHORTNAME.'</a></td>';
				  	
				  		echo '<td align="center" > <a title="Edit" href="index.php?view=edit&id='.$result->DEPTID.'"  class="btn btn-primary btn-sm  ">  <span class="fa fa-edit fw-fa"></span>Edit</a>
				  				<a title="Delete" href="controller.php?action=delete&id='.$result->DEPTID.'" class="btn btn-danger btn-sm  delete" ><span class="fa fa-trash-o fw-fa"></span>Delete</a>	 
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