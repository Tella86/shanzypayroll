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
	
	}
   
   //`LEAVEID`, `EMPLOYID`, `DATESTART`, `DATEEND`, `NODAYS`, `SHIFTTIME`, `TYPEOFLEAVE`, `REASON`, `LEAVESTATUS`, `ADMINREMARKS`, `DATEPOSTED`
	function doInsert(){
		
		if (isset($_POST['save'])){
			$EMPLOYID 	= $_POST['EMPLOYID'];
			$DATESTART 	= $_POST['DATESTART'];
			$DATEEND 	= $_POST['DATEEND'];
			$NODAYS 	= 0;
			$SHIFTTIME	 = $_POST['SHIFTTIME'];
			$TYPEOFLEAVE = $_POST['TYPEOFLEAVE'];
			$REASON 	  = $_POST['REASON'];
			$LEAVESTATUS  ='PENDING';
			$ADMINREMARKS = 'N/A';
			$DATEPOSTED   = date("Y-m-d");


			if ($SHIFTTIME == 'AM' || $SHIFTTIME == 'PM') {
				$DF = date_create($DATESTART);
				$DT = date_create($DATEEND);
				$diff = date_diff($DF , $DT );
				$NODAYS =  (( 1 + ($diff->format("%a")) ) / 2);
				
			}elseif ($SHIFTTIME == 'All Day') {
				$DF = date_create($DATESTART);
				$DT = date_create($DATEEND);
					
				$diff =  date_diff($DF , $DT );
				$NODAYS =  (1 + $diff->format("%a"));
			
			}

			$Leave = new Leave();
			$Leave->EMPLOYID    = $EMPLOYID;
			$Leave->DATESTART   = $DATESTART;
			$Leave->DATEEND     = $DATEEND;
			$Leave->NODAYS      = $NODAYS;
			$Leave->SHIFTTIME   = $SHIFTTIME;
			$Leave->TYPEOFLEAVE = $TYPEOFLEAVE;
			$Leave->REASON      = $REASON;
			$Leave->LEAVESTATUS  = $LEAVESTATUS;
			$Leave->ADMINREMARKS = $ADMINREMARKS;
			$Leave->DATEPOSTED   = $DATEPOSTED;


		$istrue = 	$Leave->create();
		if ($istrue == 1) {
			message("Your leave application been created successfully!", "success");
			redirect('index.php');
		}



		}


	}

	function doEdit(){
		if (isset($_POST['save'])){
			$LEAVEID  	  = $_POST['LEAVEID'];
			$LEAVESTATUS  = $_POST['LEAVESTATUS'];
			$ADMINREMARKS = $_POST['ADMINREMARKS'];
			$DATEPOSTED   = date("Y-m-d");
			$REMLEAVE 	= $_POST['REMLEAVE'];
			$EMPID 		= $_POST['EMPID'];
			    $Leave = new Leave();
				$Leave->LEAVESTATUS  = $LEAVESTATUS;
				$Leave->ADMINREMARKS = $ADMINREMARKS;
				$Leave->DATEPOSTED   = $DATEPOSTED;

			if ($REMLEAVE <= 0 ) {

				if ($_POST['TYPEOFLEAVE']== 'UNPAID LEAVE') {
					$Leave->update($LEAVEID);
					
					$user = new User();
					$user->AVELEAVE     = $REMLEAVE;
					$user->update($_POST['EMPID']); 
					message("Your leave application been created successfully!", "success");
					redirect('index.php');

				}else{
					if ($LEAVESTATUS == 'APPROVED'){
						$Leave->LEAVESTATUS  = 'REJECTED';
						$Leave->update($LEAVEID);
						message("Sorry You can't approved this leave!", "error");
						redirect('index.php');
					}else{
						$Leave->LEAVESTATUS  = 'REJECTED';
						$Leave->update($LEAVEID);
						message("Employee Remaining leave is not valid!", "error");
						redirect('index.php');
					}
				}
					
				

				
			}else{

				

				if ($LEAVESTATUS == 'REJECTED' || $LEAVESTATUS == 'PENDING') {
						
					$Leave->update($LEAVEID);
					message("Your leave application is  ". $LEAVESTATUS , "error");
					redirect('index.php');
				}else{

					$Leave->update($LEAVEID);
					$user = new User();
					$user->AVELEAVE     = $REMLEAVE;
					$user->update($EMPID); 
					message("Your leave application been created successfully!", "success");
					redirect('index.php');
					echo $REMLEAVE;
				}
					

				
			}
		}
	}



 
?>