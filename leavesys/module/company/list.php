<?php
	 if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }

?>
<div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-university"></i> List of Company   <a href="index.php?view=add" class="btn btn-primary  ">  <i class="fa fa-plus-circle fw-fa"></i> New</a></div>

         
        <div class="card-body">
          <div class="table-responsive">
	 		<form action="controller.php?action=delete" Method="POST">  
			   		
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				
				  <thead>
				  	<tr>
				  		<th>ID</th>
				  		<th>Company Name</th>
				  		<th width="15%" >Action</th>
				 
				  	</tr>	
				  </thead> 
				  <tbody>
				  
				  	<?php 
				  	
				  		$mydb->setQuery("SELECT * 
											FROM  `tblcompany`");
				  		$cur = $mydb->loadResultList();

						foreach ($cur as $result) {
				  		echo '<tr>';
				  		// echo '<td width="5%" align="center"></td>';
				  		echo '<td>' . $result->COMPID.'</a></td>';
				  		echo '<td>' . $result->COMPANY.'</a></td>';
				  		
				  		echo '<td align="center" >
				  		<a title="Edit" href="index.php?view=edit&id='.$result->COMPID.'"  class="btn btn-primary btn-sm  ">  <span class="fa fa-edit fw-fa"></span>Edit</a>
								 <a title="Delete" href="controller.php?action=delete&id='.$result->COMPID.'" class="btn btn-danger btn-sm  delete" ><span class="fa fa-trash-o fw-fa"></span>Delete</a>
								 			  			
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