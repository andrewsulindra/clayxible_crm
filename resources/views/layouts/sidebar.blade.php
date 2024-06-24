  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><b>{{ config('app.name') }}</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header">FEATURED</li>
          <li class="nav-item">
            <a href="/" class="nav-link {!! $menu == 'dashboard' ? 'active' : '' !!}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          @if (Auth::user()->hasAnyPermission(['View project']))
            <li class="nav-item">
              <a href="{{ url('/project') }}" class="nav-link {!! $sub_menu == 'project' ? 'active' : '' !!}">
                <i class="far fa-circle nav-icon"></i>
                <p>Project</p>
              </a>
            </li>
          @endif
          @if (Auth::user()->hasAnyPermission(['View owner']))
            <li class="nav-item">
              <a href="{{ url('/owner') }}" class="nav-link {!! $sub_menu == 'owner' ? 'active' : '' !!}">
                <i class="far fa-circle nav-icon"></i>
                <p>Owner</p>
              </a>
            </li>
          @endif
          @if (Auth::user()->hasAnyRole(['Super Admin', 'Manager']))
          <li class="nav-header">SYSTEM</li>
          <li class="nav-item has-treeview {!! $menu == 'users' ? 'menu-open' : '' !!}">
            <a href="#" class="nav-link {!! $menu == 'users' ? 'active' : '' !!}">
              <i class="nav-icon far fa-user"></i>
              <p>
                Users Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/users') }}" class="nav-link {!! $sub_menu == 'user' ? 'active' : '' !!}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link {!! $sub_menu == 'role' ? 'active' : '' !!}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Role</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>