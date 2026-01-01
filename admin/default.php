      <?php include './layout/topheader.php'; ?>
       
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <?php
            $link =  $_SERVER['PHP_SELF'];
              
            $decoded_string = base64_decode($_GET['p']);
              
              $page =  './body/'.$decoded_string;
              
              // echo  $page;
              
              if("dashboard.php" === $decoded_string){
                  //echo "DASHBOARD";
                  include('./body/dashboard.php');
              }else if( file_exists($page)){
                  //echo "################## ".$page;
                  include($page);
              }
            ?>
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
     
      <?php include './layout/bottomfooter.php'; ?>
     