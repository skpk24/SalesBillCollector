<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand" style="background: #ffffff">
          <!--begin::Brand Link-->
          <a href="./default.php" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="./admin/img/logo-files/logo.png"
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
			    <li class="nav-item"><a href="default.php?p=ZGF0YS5waHA=" class="nav-link<?php if(!empty($_GET['p']) && "ZGF0YS5waHA=" === $_GET['p']){?> active<?php } ?> "><i class="nav-icon bi bi-person"></i><p>Sales Bills</p></a></li>
                

            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>