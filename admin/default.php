      <?php include 'session.php'; ?>
      <?php 
         if(!require_permission('super:admin')){
      ?>
      <?php include './layout/topheader.php'; ?>

      
       
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <?php
            if (!empty($_GET['p'])) {
              $link =  $_SERVER['PHP_SELF'];
                
              $decoded_string = base64_decode($_GET['p']);
                
                $page =  './body/'.$decoded_string;
                
                 //echo  "DDDDDDDD ".$page;
                
                if("dashboard.php" === $decoded_string){
                    //echo "DASHBOARD";
                    include('./body/dashboard.php');
                }else if( file_exists($page)){
                    //echo "################## ".$page;
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
     