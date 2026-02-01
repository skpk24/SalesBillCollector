<!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <!--li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li-->
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <!--li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li-->
            <!--end::Navbar Search-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <!--img
                  src="./img/user2-160x160.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                /-->
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-person-fill" viewBox="0 0 16 16">
                  <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2m-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11"/>
                </svg>
                
                <span class="d-none d-md-inline"><?php echo !empty($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User Name'; ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="./img/user2-160x160.jpg"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    <?php echo !empty($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User Name'; ?> - 
                    <span id="userRoles">
                      <!-- Roles will be populated here -->
                    </span>
                    <small>Member since <?php echo !empty($_SESSION['created_at']) ? date("M Y", strtotime($_SESSION['created_at'])) : 'Joined'; ?></small>
                  </p>
                </li>
                <!--end::User Image-->
                
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="default.php?p=dXNlcnMucGhw" class="btn btn-default btn-flat">Profile</a>
                  <a href="logout.php" class="btn btn-default btn-flat float-end">Sign out</a>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->

<script type="text/javascript">
  var authData = <?php echo json_encode($_SESSION['user_schema']); ?>;

// Use $.each to iterate over the object
let conter = 0 ;

let role = "";


Object.entries(authData).forEach(([roleName, details]) => {
    console.log(`Role: ${roleName}`);

    if(conter == 0) {
        //console.log("Role: " + role);
        role = roleName;
        conter++;
    }else{
        role = role + "<br/>& " + roleName;
        console.log("Role: " + role);
    }
    
    // Loop through the permissions array for this role
    details.permissions.forEach(perm => {
        console.log(` - Has Permission: ${perm}`);
    });
});

document.getElementById("userRoles").innerHTML = role;
</script>