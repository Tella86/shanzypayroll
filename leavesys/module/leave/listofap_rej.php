<?php
	 if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }

?>
<div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-table"></i> List of Leave Application    <?php if ($_SESSION['EMPPOSITION'] == 'Administrator') 
          { 
          	echo '
          <a href="index.php?view=add" class="btn btn-primary  ">  <i class="fa fa-plus-circle fw-fa"></i> New 
 </a>
 ';
      	  }
      	  ?>
 	</div>

         
        <div class="card-body">
          <div class="table-responsive">
	 		    <form action="controller.php?action=delete" Method="POST">  
			    		
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				
				  <thead>
				  	<tr>
				  		<th>EMP. ID</th>
				  		<th>DATE FROM</th>
				  		<th>DATE TO</th>
				  		<th>NO. OF DAYS</th>
				  		<th>SHIFTIME</th>
				  		<th>TYPE</th>
				  		<th>REASON</th>
				  		<th>STATUS</th>
				  		<th>REMARKS</th>
				  		<th>DATE POSTED</th>
				  		<?php 
				  		if ($_SESSION['EMPPOSITION'] == 'Normal user') {

				  		}else{
				  			echo " <th>Action</th>";
				  		}
				  		?>
						
				  	</tr>	
				  </thead>
				  <tbody>
				  	<?php 
				  	//`LEAVEID`, `EMPLOYID`, `DATESTART`, `DATEEND`, `NODAYS`, `SHIFTTIME`, `TYPEOFLEAVE`, `REASON`, `LEAVESTATUS`, `ADMINREMARKS`, `DATEPOSTED`
					
				  	if ($_SESSION['EMPPOSITION'] == 'Normal user') {
				  		global $mydb;
			  		$mydb->setQuery("select  * from tblleave where EMPLOYID=". $_SESSION['EMPLOYID']." AND LEAVESTATUS='APPROVED'" );
					$cur = $mydb->loadResultList();
				  		foreach ($cur as $Defaults) {
					  		echo '<tr>';

						  		echo '<td>' . $Defaults->EMPLOYID.'</a></td>';
						  		echo '<td>'. $Defaults->DATESTART.'</td>';
						  		echo '<td>'. $Defaults->DATEEND.'</td>';
						  		echo '<td>'. $Defaults->NODAYS.'</td>';
						  		echo '<td>' . $Defaults->SHIFTTIME.'</a></td>';
						  		echo '<td>'. $Defaults->TYPEOFLEAVE.'</td>';
						  		echo '<td>'. $Defaults->REASON.'</td>';
						  		echo '<td>'. $Defaults->LEAVESTATUS.'</td>';
						  		echo '<td>'. $Defaults->ADMINREMARKS.'</td>';
						  		echo '<td>'. $Defaults->DATEPOSTED.'</td>';
					  		echo '</tr>';
				  		} 
				  	}elseif ($_SESSION['EMPPOSITION'] == 'Supervisor user' || $_SESSION['EMPPOSITION'] == 'Manager user') {
				  		global $mydb;
			  		$mydb->setQuery("select  * from tblleave where LEAVESTATUS='APPROVED' AND `EMPLOYID` IN ( SELECT `EMPLOYID` from tblemployee WHERE `COMPANY`='". $_SESSION['COMPANY']  ."' AND `DEPARTMENT`='". $_SESSION['DEPARTMENT']  ."')  " );
					$cur = $mydb->loadResultList();
				  		foreach ($cur as $Defaults) {
					  		echo '<tr>';

					  		echo '<td>' . $Defaults->EMPLOYID.'</a></td>';
					  		echo '<td>'. $Defaults->DATESTART.'</td>';
					  		echo '<td>'. $Defaults->DATEEND.'</td>';
					  		echo '<td>'. $Defaults->NODAYS.'</td>';
					  		echo '<td>' . $Defaults->SHIFTTIME.'</a></td>';
					  		echo '<td>'. $Defaults->TYPEOFLEAVE.'</td>';
					  		echo '<td>'. $Defaults->REASON.'</td>';
					  		echo '<td>'. $Defaults->LEAVESTATUS.'</td>';
					  		echo '<td>'. $Defaults->ADMINREMARKS.'</td>';
					  		echo '<td>'. $Defaults->DATEPOSTED.'</td>';
					  		echo $active = "";
					  		
					  		echo '<td align="center" > <a title="Edit" href="index.php?view=edit&id='.$Defaults->LEAVEID.'"  class="btn btn-primary btn-xs  ">  <span class="fa fa-edit fw-fa"></span></a>
					  					 </td>';
					  		echo '</tr>';
				  		} 
				  	}elseif ($_SESSION['EMPPOSITION'] == 'Administrator' || $_SESSION['EMPPOSITION'] == 'Boss User') {
				  		global $mydb;
			  		$mydb->setQuery("select  * from tblleave where LEAVESTATUS='APPROVED' AND `EMPLOYID` IN ( SELECT `EMPLOYID` from tblemployee WHERE `COMPANY`='". $_SESSION['COMPANY']  ."' )  " );
					$cur = $mydb->loadResultList();
				  		foreach ($cur as $Defaults) {
					  		echo '<tr>';

					  		echo '<td>' . $Defaults->EMPLOYID.'</a></td>';
					  		echo '<td>'. $Defaults->DATESTART.'</td>';
					  		echo '<td>'. $Defaults->DATEEND.'</td>';
					  		echo '<td>'. $Defaults->NODAYS.'</td>';
					  		echo '<td>' . $Defaults->SHIFTTIME.'</a></td>';
					  		echo '<td>'. $Defaults->TYPEOFLEAVE.'</td>';
					  		echo '<td>'. $Defaults->REASON.'</td>';
					  		echo '<td>'. $Defaults->LEAVESTATUS.'</td>';
					  		echo '<td>'. $Defaults->ADMINREMARKS.'</td>';
					  		echo '<td>'. $Defaults->DATEPOSTED.'</td>';
					  		echo $active = "";
					  		
					  		echo '<td align="center" > <a title="Edit" href="index.php?view=edit&id='.$Defaults->LEAVEID.'"  class="btn btn-primary btn-xs  ">  <span class="fa fa-edit fw-fa"></span></a>
					  					 </td>';
					  		echo '</tr>';
				  		} 
				  	}

				  	

						
				  	?>
				  </tbody>
				 
				</table>
			
				</form>
	
       </div>
        </div>
      
      </div>