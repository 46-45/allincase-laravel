<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Allincase</title>

    <!-- Bootstrap css -->
    <link href="{{ asset('build/assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" />

    <!-- Style css (main app) -->
    <link href="{{ asset('build/assets/app-B7LWw5fR.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/assets/_updatestyle1-B8sP10EJ.css') }}" rel="stylesheet" />

    <!-- Sidemenu css -->
    <link href="{{ asset('build/assets/sidemenu-BP023z1y.css') }}" rel="stylesheet" />

    <!-- P-scroll bar css-->
    <link href="{{ asset('build/assets/plugins/p-scrollbar/p-scrollbar.css') }}" rel="stylesheet" />

    <!-- Icons css-->
    <link href="{{ asset('build/assets/plugins/icons/icons.css') }}" rel="stylesheet" />

    <!-- Select2 css -->
    <link href="{{ asset('build/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- Toastr css -->
    <link href="{{ asset('build/assets/plugins/toastr/toastr.css') }}" rel="stylesheet" />

    <!-- Animated css -->
    <link href="{{ asset('build/assets/animated-DaFtu_tu.css') }}" rel="stylesheet" />

    <!-- Google Fonts -->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");
    </style>

    @yield('styles')

    <!-- Jquery js-->
    <script src="{{ asset('build/assets/plugins/jquery/jquery.min.js') }}"></script>
</head>
<body class="app sidebar-mini">

<div class="page">
    <div class="page-main">

        @if(isset($admin))
        <!-- Sidebar -->
        <aside class="app-sidebar">
            <div class="app-sidebar__logo">
                <a class="header-brand" href="/admin/dashboard">
                    <span style="color:#fff;font-size:20px;font-weight:700;">All<span style="color:#6259ca;">in</span>case</span>
                </a>
            </div>
            <div class="app-sidebar3">
                <div class="app-sidebar__user">
                    <div class="dropdown user-pro-body text-center">
                        <div class="user-pic">
                            <img src="{{ asset('build/assets/images/users/user-profile.png') }}" class="avatar-xxl rounded-circle mb-1" alt="admin">
                        </div>
                        <div class="user-info">
                            <h5 class="mb-2">{{ $admin->full_name }}</h5>
                            <span class="text-muted app-sidebar__user-name text-sm">Super Admin</span>
                        </div>
                    </div>
                </div>
                <ul class="side-menu">
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">
                            <i class="fe fe-home side-menu__icon"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <li class="sub-category"><h3>Management</h3></li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/lawyers*') ? 'active' : '' }}" href="/admin/lawyers">
                            <i class="fe fe-users side-menu__icon"></i>
                            <span class="side-menu__label">Lawyers</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/clients*') ? 'active' : '' }}" href="/admin/clients">
                            <i class="fe fe-user side-menu__icon"></i>
                            <span class="side-menu__label">Clients</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/cases*') ? 'active' : '' }}" href="/admin/cases">
                            <i class="fe fe-briefcase side-menu__icon"></i>
                            <span class="side-menu__label">Cases</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/categories*') ? 'active' : '' }}" href="/admin/categories">
                            <i class="fe fe-folder side-menu__icon"></i>
                            <span class="side-menu__label">Categories</span>
                        </a>
                    </li>
                    <li class="sub-category"><h3>Settings</h3></li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/pricing*') ? 'active' : '' }}" href="/admin/pricing">
                            <i class="fe fe-dollar-sign side-menu__icon"></i>
                            <span class="side-menu__label">Pricing</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/content*') ? 'active' : '' }}" href="/admin/content/terms">
                            <i class="fe fe-file-text side-menu__icon"></i>
                            <span class="side-menu__label">Content Pages</span>
                        </a>
                    </li>
                    <li class="sub-category"><h3>Account</h3></li>
                    <li class="slide">
                        <a class="side-menu__item" href="/admin/logout">
                            <i class="fe fe-log-out side-menu__icon"></i>
                            <span class="side-menu__label">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- /Sidebar -->

        <div class="app-content main-content">
            <div class="side-app">
                <!-- Header -->
                <div class="app-header header header-main">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="app-sidebar__toggle ms-0 me-2" data-bs-toggle="sidebar">
                                <a class="open-toggle" href="#"><i class="feather feather-menu"></i></a>
                                <a class="close-toggle" href="#"><i class="feather feather-x"></i></a>
                            </div>
                            <div class="d-flex order-lg-2 my-auto ms-auto align-items-center">
                                <div class="d-flex align-items-center me-3">
                                    <i class="feather feather-clock me-2"></i>
                                    <span class="fs-13">{{ now()->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="dropdown">
                                    <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown">
                                        <span class="avatar avatar-md brround bg-primary text-white">{{ strtoupper(substr($admin->full_name, 0, 1)) }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <div class="p-3 text-center border-bottom">
                                            <h5 class="mb-0">{{ $admin->full_name }}</h5>
                                            <small class="text-muted">Administrator</small>
                                        </div>
                                        <a class="dropdown-item" href="/admin/logout"><i class="fe fe-log-out me-2"></i> Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Header -->

                <!-- Page Header -->
                <div class="page-header d-xl-flex d-block">
                    <div class="page-leftheader">
                        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">@yield('title', 'Dashboard')</span></h4>
                    </div>
                </div>
                <!-- /Page Header -->

                @yield('content')

            </div>
        </div>
        @else
            @yield('content')
        @endif

    </div>

    <!-- Footer -->
    @if(isset($admin))
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">
                    &copy; {{ date('Y') }} Allincase. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
    @endif
</div>

<!-- Back to top -->
<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

<!-- Bootstrap js-->
<script src="{{ asset('build/assets/plugins/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('build/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- Sidemenu js-->
<script src="{{ asset('build/assets/plugins/sidemenu/sidemenu.js') }}"></script>

<!-- P-scroll js-->
<script src="{{ asset('build/assets/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
<script src="{{ asset('build/assets/plugins/p-scrollbar/p-scroll1.js') }}"></script>

<!-- Select2 js -->
<script src="{{ asset('build/assets/plugins/select2/select2.full.min.js') }}"></script>

<!-- Toastr js -->
<script src="{{ asset('build/assets/plugins/toastr/toastr.min.js') }}"></script>

<!-- Custom js -->
<script src="{{ asset('build/assets/custom-DbHqSD4P.js') }}"></script>

@if(session('success'))
<script>toastr.success("{{ session('success') }}");</script>
@endif
@if(session('error'))
<script>toastr.error("{{ session('error') }}");</script>
@endif

@yield('scripts')

</body>
</html>
