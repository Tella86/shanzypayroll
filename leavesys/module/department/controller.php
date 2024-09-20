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
	//DEPTNAME,DEPTSHORTNAME
		$dept = new Department();
		$DEPTNAME		    = $_POST['name'];
		$DEPTSHORTNAME		= $_POST['deptshrtname'];
	
				$dept->DEPTNAME     = $DEPTNAME;
				$dept->DEPTSHORTNAME     = $DEPTSHORTNAME;
				 $istrue = $dept->create(); 
				 if ($istrue == 1){
				 	message("New Department [". $DEPTNAME ."] has beem created successfully!", "success");
				 	redirect('index.php');
				 	
				 }

		}

	}

	function doEdit(){
		if(isset($_POST['save'])){

			$dept = new Department();
			$DEPTNAME		    = $_POST['name'];
			$DEPTSHORTNAME		= $_POST['deptshrtname'];
			$dept->DEPTNAME     = $DEPTNAME;
			$dept->DEPTSHORTNAME     = $DEPTSHORTNAME;
			$dept->update($_POST['DEPTID']);

			message("[". $_POST['name'] ."] has been updated!", "success");
			redirect("index.php");
		}
	}


function doDelete(){
		if(isset($_GET['id'])){

			$Company = new Department();
			$Company->delete($_GET['id']);

			message("Department has been deleted!", "success");
			redirect("index.php");
		}
	}
	
 
?>