<?php
require_once("../../include/initialize.php");
 if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
 $title="Leave Module"; 
 $header=$view; 
switch ($view) {
	case 'list' :
		$content    = 'list.php';		
		break;

	case 'add' :
		$content    = 'add.php';		
		break;

	case 'edit' :
		$content    = 'edit.php';		
		break;
    case 'view' :
		$content    = 'view.php';		
		break;
	case 'approved' :
		$content    = 'listofap_rej.php';		
		break;
	case 'rejected' :
		$content    = 'listofrej.php';		
		break;

	default :
		$content    = 'list.php';		
}
require_once ("../../theme/template.php");
?>
  
