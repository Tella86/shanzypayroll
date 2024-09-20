<?php
require_once("include/initialize.php");
 if (!isset($_SESSION['EMPID'])){
      redirect(web_root."/index.php");
     }
     ?>
<div class="row">
         <div class="col-lg-12">
            <h1 class="page-header">Dashboard for <?php echo $_SESSION['EMPPOSITION'];?></h1>
          </div>
          <!-- /.col-lg-12 -->
      
       </div>
  <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-comments"></i>
              </div>
              <div class="mr-5">
              	<?php 
              	/*	$Project = New Project();
              	$cur =  $Project->noOfprojects();
              	echo $cur->noOfprojects. ' No of Projects';*/
              	?>

             </div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="<?php echo WEB_ROOT; ?>module/project/">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5"><?php 
              /*		$Project = New Project();
              	$cur =  $Project->noOfprojectsOngoing();
              	echo $cur->noOfprojects. ' of ongoing Projects';*/
              	?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="<?php echo WEB_ROOT; ?>module/project/">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-shopping-cart"></i>
              </div>
              <div class="mr-5"><?php 
              	/*	$Project = New Project();
              	$cur =  $Project->noOfprojectsFinished();
              	echo $cur->noOfprojects. ' of finished Projects';*/
              	?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="<?php echo WEB_ROOT; ?>module/project/">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-support"></i>
              </div>
              <div class="mr-5"><?php 
              		/*$Defaults = New Defaults();
              	$cur =  $Defaults->noOfExpnses();
              	echo $cur->noOfExpnses. ' No of Expenses';*/
              	?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="<?php echo WEB_ROOT; ?>module/expenses/">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
      </div>
  <!-- /.panel -->

  