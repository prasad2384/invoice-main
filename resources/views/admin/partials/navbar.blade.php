<style>
    .rounded-circle {
        width: 30px
    }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <div class="d-flex">
                {{-- <a href="#" class="btn position-relative mt-2">
            <i class="far fa-envelope fa-lg"></i>
            <span class="badge badge bg-danger rounded-pill top-0 start-50 position-absolute">
                0 <span class="visually-hidden">unread messages</span>
            </span>
        </a> --}}
                <div class="dropdown d-inline-block">
                    @php
                        // Fetch the first record from the settings table
                        $setting = \App\Models\Setting::first();
                    @endphp
                    <button type="button" class="btn header-item bg-soft-light" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle" 
     src="{{ isset($setting->update_logo) ? asset('images/' . $setting->update_logo) : asset('images/default-profile.jpg') }}" 
     alt="User Profile">
                        <span>{{ auth()->user()->name }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="{{ url('admin/change-password') }}"><i class="fas fa-lock"></i>
                            Change Password</a>
                        <div class="dropdown-divider"></div>

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); 
                document.getElementById('logout-form').submit();"
                            class="dropdown-item ai-icon">
                            <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18"
                                height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span class="ms-2">Logout </span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<script>
    $('#set-school').change(function() {
        if ($('#set-school').val() != 0) {
            $("#school-form").submit();
        }
    });
</script>
