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

	case 'reset' :
	doresetpass();
	break;


 
	}
	function doresetpass()
	{
		$oldpass = sha1($_POST['CURPASS']);
		$newpass = $_POST['newpass'];
		$cpass = $_POST['cpass'];
		$resetparam = $_GET['from'];
		//check if old pass exist!
		if ($oldpass == $_SESSION['PASSWRD'] ) {
			# code...
			//check if new and cpass is equal
		
			if ($newpass == $cpass) {

				$user = new User();
				$user->PASSWRD     = sha1($newpass);
				$user->update($_GET['id']); 
				// if ($istrue == 1){
				 	message("Your password has been reset successfully!", "success");

				 	if ($resetparam=='emp'){
				 		redirect('index.php');
				 	}else{
				 		redirect('index.php?view=edit&id='.$_GET['id']);
				 	}
				 	
				 	
				
			}else{
				
				message("Password and Confirm Password not equal!","error");
				redirect("index.php?view=reset&id=".$_GET['id']);
			}
			

		}else{
			message("Incorrect current Password!","error");
			redirect("index.php?view=reset&id=".$_GET['id']);
		}
	}
   
	function doInsert(){
	
	if (isset($_POST['save']) ) {
	
		
//`EMPID`, `EMPNAME`, `EMPPOSITION`, `USERNAME`, `PASSWRD`, `ACCSTATUS`, `EMPSEX`, `COMPANY`, `DEPARTMENT`
		$user = new User();
		$EMPNAME		= $_POST['name'];
		$EMPPOSITION    = $_POST['type'];
		$USERNAME 	    = $_POST['username'];
		$PASSWRD 		= $_POST['pass'];
		$ACCSTATUS 		= 'YES';
		$EMPSEX 		= $_POST['sex'];
		$COMPANY 		= $_POST['company'];
		$DEPARTMENT 	= $_POST['department'];
		$EMPLOYID 		= $_POST['employid'];
	

				$user->EMPNAME     = $EMPNAME;
				$user->EMPPOSITION = $EMPPOSITION;
				$user->USERNAME    = $USERNAME;
				$user->PASSWRD     = sha1($PASSWRD);
				$user->ACCSTATUS   = $ACCSTATUS;
				$user->EMPSEX      = $EMPSEX;
				$user->COMPANY     = $COMPANY;
				$user->DEPARTMENT  = $DEPARTMENT;
				$user->EMPLOYID    = $EMPLOYID;
				
				 $istrue = $user->create(); 
				 if ($istrue == 1){
				 	message("New Employee [". $EMPNAME ."] has beem created successfully!", "success");
				 	redirect('index.php');
				 	
				 }

		}

	}

	function doEdit(){
	if(isset($_POST['save'])){

			$user = New User(); 
			$user->EMPNAME		= $_POST['name'];
			$user->EMPPOSITION    = $_POST['type'];
			$user->USERNAME 	    = $_POST['username'];
		//	$user->PASSWRD 		= sha1($_POST['pass']);
			$user->ACCSTATUS 		= 'YES';
			$user->EMPSEX 		= $_POST['sex'];
			$user->COMPANY 		= $_POST['company'];
			$user->DEPARTMENT 	= $_POST['department'];
			$user->EMPLOYID 	= $_POST['employid'];
			$user->update($_POST['empid']);

			message("[". $_POST['name'] ."] has been updated!", "success");
			redirect("index.php");
		}
	}


	function doDelete(){
		
		
				$id = 	$_GET['id'];

				$user = New User();
	 		 	$user->delete($id);
			 
			message("User has been Deleted!","success");
			redirect('index.php');
		// }
		// }

		
	}

	
 
?>