<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @php
        // Fetch the first record from the settings table
        $setting = \App\Models\Setting::first();
    @endphp
    <a href="{{ url('/admin/dashboard') }}" class="brand-link" style="text-decoration:none">
       <img src="{{ isset($setting->update_logo) ? asset('images/' . $setting->update_logo) : asset('images/default-logo.jpg') }}" 
         alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span
            class="brand-text font-weight-light">{{ $setting->company_name == '' ? 'Webroot Infosoft' : $setting->company_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/invoices') }}" class="nav-link">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>Invoices</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/users') }}" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/settings') }}" class="nav-link">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-cog"></i>
                    <p>Settings<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="nav-icon fas fa"></i>
                        <p>Item 1</p>
                      </a>
                    </li>
                  </ul>
                 </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
