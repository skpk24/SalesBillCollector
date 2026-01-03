<?php include './admin/session.php'; ?>
<?php 
    if(!require_permission('staff:full')){
?>
<?php include './layout/topheader.php'; ?>

<div class="app-content">
  <!--begin::Container-->
  <div class="container-fluid">
    <?php
    if (!empty($_GET['p'])) {
        $decoded_string = base64_decode($_GET['p']);
        
        $page =  './'.$decoded_string;
        
        if("dashboard.php" === $decoded_string){
            //echo "DASHBOARD";
            include('./dashboard.php');
        }else if("editbill.php" === $decoded_string){
            include('./admin/body/'.$decoded_string);
        }else if( file_exists($page)){
            include($page);
        }
      }else{
          echo "<h3>Welcome to Nandi Enterprises.</h3>";
      }

    ?>
  </div>
  <!--end::Container-->
</div>
<!--end::App Content-->
<?php }?>
<?php include './layout/bottomfooter.php'; ?>