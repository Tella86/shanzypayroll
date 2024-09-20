<?php
require_once ("../../include/initialize.php");
	  if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
	case 'add' :
	doInsert();
	break;
	
	case 'edit' :
	doEdit();
	break;
	
	case 'delete' :
	doDelete();
	break;

	
 
	}
   
	function doInsert(){
	
	if (isset($_POST['save']) ) {
	//`LEAVTID`, `LEAVETYPE`, `DESCRIPTION`
		$leave = new Leavetype();
		$LEAVETYPE		    = $_POST['name'];
		$DESCRIPTION		= $_POST['DESCRIPTION'];
	
				$leave->LEAVETYPE     = $LEAVETYPE;
				$leave->DESCRIPTION     = $DESCRIPTION;
				 $istrue = $leave->create(); 
				 if ($istrue == 1){
				 	message("New Leave Type [". $LEAVETYPE ."] has beem created successfully!", "success");
				 	redirect('index.php');
				 	
				 }

		}

	}

	function doEdit(){
		if(isset($_POST['save'])){

			$leave = new Leavetype();
			$LEAVETYPE		    = $_POST['name'];
			$DESCRIPTION		= $_POST['DESCRIPTION'];
			$leave->LEAVETYPE     = $LEAVETYPE;
			$leave->DESCRIPTION     = $DESCRIPTION;
			$leave->update($_POST['LEAVTID']);

			message("[". $_POST['name'] ."] has been updated!", "success");
			redirect("index.php");
		}
	}
function doDelete(){
		
		
				$id = 	$_GET['id'];

				$user = New Leavetype();
	 		 	$user->delete($id);
			 
			message("Leave type has been Deleted!","success");
			redirect('index.php');
		// }
		// }

		
	}


	
 
?>