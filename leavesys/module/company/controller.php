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


	case 'photos' :
	doupdateimage();
	break;

 
	}
   
	function doInsert(){
	
	if (isset($_POST['save']) ) {
	
		

		$Company = new Company();
		$COMPANY		= $_POST['name'];
	
	

				$Company->COMPANY     = $COMPANY;
				
				 $istrue = $Company->create(); 
				 if ($istrue == 1){
				 	message("New Company [". $COMPANY ."] has beem created successfully!", "success");
				 	redirect('index.php');
				 	
				 }

		}

	}

	function doEdit(){
		if(isset($_POST['save'])){

			$Company = new Company();
			$Company->COMPANY     = $_POST['name'];
			$Company->update($_POST['COMPID']);

			message("[". $_POST['name'] ."] has been updated!", "success");
			redirect("index.php");
		}
	}

function doDelete(){
		if(isset($_GET['id'])){

			$Company = new Company();
			$Company->delete($_GET['id']);

			message("Company has been deleted!", "success");
			redirect("index.php");
		}
	}


	
 
?>