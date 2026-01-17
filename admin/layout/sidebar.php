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
			        <li class="nav-item"><a href="default.php?p=ZGFzaGJvYXJkLnBocA==" class="nav-link<?php if(!empty($_GET['p']) && "ZGFzaGJvYXJkLnBocA==" === $_GET['p']){?> active"<?php } ?>"><i class="nav-icon  bi bi-circle"></i><p>Dashboard</p></a></li>
			        <li class="nav-item"><a href="default.php?p=ZGF0YS5waHA=" class="nav-link<?php if(!empty($_GET['p']) && ("ZGF0YS5waHA=" === $_GET['p'] || "ZWRpdGJpbGwucGhw" === $_GET['p'])){?> active<?php } ?> "><i class="nav-icon bi bi-person"></i><p>Sales Bills</p></a></li>
              <li class="nav-item"><a href="default.php?p=cGF5bWVudHMucGhw" class="nav-link<?php if(!empty($_GET['p']) && "cGF5bWVudHMucGhw" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-credit-card"></i><p>Payments</p></a></li>
              <li class="nav-item"><a href="default.php?p=dXNlcnMucGhw" class="nav-link<?php if(!empty($_GET['p']) && "dXNlcnMucGhw" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person"></i><p>Users</p></a></li>
              <li class="nav-item"><a href="default.php?p=cm9sZXMucGhw" class="nav-link<?php if(!empty($_GET['p']) && "cm9sZXMucGhw" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person-rolodex"></i><p>Roles</p></a></li>
              <li class="nav-item"><a href="default.php?p=cGVybWlzc2lvbnMucGhw" class="nav-link<?php if(!empty($_GET['p']) && "cGVybWlzc2lvbnMucGhw" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person-lines-fill"></i><p>Permissions</p></a></li>
              <li class="nav-item"><a href="default.php?p=dXNlcl9yb2xlcy5waHA=" class="nav-link<?php if(!empty($_GET['p']) && "dXNlcl9yb2xlcy5waHA=" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon  bi bi-person-plus"></i><p>User & Roles</p></a></li>
              <li class="nav-item"><a href="default.php?p=cm9sZV9wZXJtaXNzaW9ucy5waHA=" class="nav-link<?php if(!empty($_GET['p']) && "cm9sZV9wZXJtaXNzaW9ucy5waHA=" === $_GET['p']){?> active<?php } ?>"><i class="nav-icon bi bi-person-gear"></i><p>Role & Permissions</p></a></li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>