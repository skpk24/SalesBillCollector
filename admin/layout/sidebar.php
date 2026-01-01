<!--aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index.php" class="brand-link">
    <span class="brand-text font-weight-light">RBAC Admin</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column">
        <li class="nav-item"><a href="users.php" class="nav-link"><i class="nav-icon fas fa-user"></i><p>Users</p></a></li>
        <li class="nav-item"><a href="roles.php" class="nav-link"><i class="nav-icon fas fa-user-tag"></i><p>Roles</p></a></li>
        <li class="nav-item"><a href="permissions.php" class="nav-link"><i class="nav-icon fas fa-key"></i><p>Permissions</p></a></li>
        <li class="nav-item"><a href="user_roles.php" class="nav-link"><i class="nav-icon fas fa-link"></i><p>User & Roles</p></a></li>
        <li class="nav-item"><a href="role_permissions.php" class="nav-link"><i class="nav-icon fas fa-link"></i><p>Role & Permissions</p></a></li>
      </ul>
    </nav>
  </div>
</aside-->

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand" style="background: #ffffff">
          <!--begin::Brand Link-->
          <a href="./default.php" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="./img/logo-files/logo.png"
              alt="Nandi Enterprises"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <!--span class="brand-text fw-light">AdminLTE 4</span-->
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
			    <li class="nav-item"><a href="default.php?p=ZGFzaGJvYXJkLnBocA==" class="nav-link<?php if("ZGFzaGJvYXJkLnBocA==" === $_GET['p']){?> active"<?php } ?>"><i class="nav-icon  bi bi-circle"></i><p>Dashboard</p></a></li>
			    <li class="nav-item"><a href="default.php?p=ZGF0YS5waHA=" class="nav-link<?php if("ZGF0YS5waHA=" === $_GET['p']){?> active<?php } ?> "><i class="nav-icon bi bi-person"></i><p>Sales Bills</p></a></li>
                <li class="nav-item"><a href="default.php?p=dXNlcnMucGhw" class="nav-link<?php if("dXNlcnMucGhw" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person"></i><p>Users</p></a></li>
                <li class="nav-item"><a href="default.php?p=cm9sZXMucGhw" class="nav-link<?php if("cm9sZXMucGhw" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person-rolodex"></i><p>Roles</p></a></li>
                <li class="nav-item"><a href="default.php?p=cGVybWlzc2lvbnMucGhw" class="nav-link<?php if("cGVybWlzc2lvbnMucGhw" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person-lines-fill"></i><p>Permissions</p></a></li>
                <li class="nav-item"><a href="default.php?p=dXNlcl9yb2xlcy5waHA=" class="nav-link<?php if("dXNlcl9yb2xlcy5waHA=" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon  bi bi-person-plus"></i><p>User & Roles</p></a></li>
                <li class="nav-item"><a href="default.php?p=cm9sZV9wZXJtaXNzaW9ucy5waHA=" class="nav-link<?php if("cm9sZV9wZXJtaXNzaW9ucy5waHA=" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person-gear"></i><p>Role & Permissions</p></a></li>
			 
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>