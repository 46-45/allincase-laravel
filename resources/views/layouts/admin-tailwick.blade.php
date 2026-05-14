<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin') | Allincase Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="/tailwick/favicon-CK1QI2Xs.ico">

    <script>
        (function () {
            const html = document.documentElement;
            const storageKey = "__TAILWICK_CONFIG__";
            const savedConfig = sessionStorage.getItem(storageKey);

            const defaultConfig = {
                dir: "ltr",
                theme: "light",
                sidenav: {
                    color: "light",
                    size: "default",
                },
            };

            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light";
            }

            const htmlConfig = {
                dir: html.getAttribute("dir") || defaultConfig.dir,
                theme: html.getAttribute("data-theme") === 'system'
                    ? getSystemTheme()
                    : html.getAttribute("data-theme") || (defaultConfig.theme === 'system' ? getSystemTheme() : defaultConfig.theme),
                sidenav: {
                    color: html.getAttribute("data-sidenav-color") || defaultConfig.sidenav.color,
                    size: html.getAttribute("data-sidenav-size") || defaultConfig.sidenav.size,
                },
            };

            window.defaultConfig = structuredClone(htmlConfig);
            let config = savedConfig ? JSON.parse(savedConfig) : htmlConfig;
            window.config = config;

            html.setAttribute("dir", config.dir);
            html.setAttribute("data-theme", config.theme);
            html.setAttribute("data-sidenav-color", config.sidenav.color);

            if (config.sidenav.size) {
                let size = config.sidenav.size;
                if (window.innerWidth <= 1140) {
                    size = "offcanvas";
                }
                html.setAttribute("data-sidenav-size", size);
            }
        })();
    </script>

    <link rel="modulepreload" crossorigin href="/tailwick/app-BxTRRtUp.js">
    <link rel="stylesheet" crossorigin href="/tailwick/app-0ZOPNGSF.css">
    @yield('styles')
</head>

<body>

    <div class="wrapper">

        <!-- Start Sidebar -->
        <aside id="app-menu" class="app-menu">

            <!-- Sidenav Menu Brand Logo -->
            <a href="/admin/dashboard" class="logo-box sticky top-0 flex min-h-topbar-height items-center justify-start px-6 backdrop-blur-xs">
                <div class="logo-light">
                    <span class="logo-lg text-lg font-bold text-white">All<span class="text-primary">in</span>case</span>
                    <span class="logo-sm text-lg font-bold text-white">A</span>
                </div>
                <div class="logo-dark">
                    <span class="logo-lg text-lg font-bold text-default-800">All<span class="text-primary">in</span>case</span>
                    <span class="logo-sm text-lg font-bold text-default-800">A</span>
                </div>
            </a>

            <!-- Sidenav Menu Toggle Button -->
            <div class="absolute top-0 end-5 flex h-topbar items-center justify">
                <button id="button-hover-toggle">
                    <i class="iconify tabler--circle size-5"></i>
                </button>
            </div>

            <!-- Sidenav Menu Item Link -->
            <div class="relative min-h-0 flex-grow">
                <div class="size-full" data-simplebar>
                    <ul class="side-nav p-3 hs-accordion-group">
                        <li class="menu-title">
                            <span>Overview</span>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">
                                <span class="menu-icon"><i data-lucide="monitor-dot"></i></span>
                                <span class="menu-text">Dashboard</span>
                            </a>
                        </li>

                        <li class="menu-title">
                            <span>Management</span>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link {{ request()->is('admin/lawyers*') ? 'active' : '' }}" href="/admin/lawyers">
                                <span class="menu-icon"><i data-lucide="users"></i></span>
                                <span class="menu-text">Lawyers</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link {{ request()->is('admin/clients*') ? 'active' : '' }}" href="/admin/clients">
                                <span class="menu-icon"><i data-lucide="user"></i></span>
                                <span class="menu-text">Clients</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link {{ request()->is('admin/cases*') ? 'active' : '' }}" href="/admin/cases">
                                <span class="menu-icon"><i data-lucide="briefcase"></i></span>
                                <span class="menu-text">Cases</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="/admin/categories">
                                <span class="menu-icon"><i data-lucide="folder"></i></span>
                                <span class="menu-text">Categories</span>
                            </a>
                        </li>

                        <li class="menu-title">
                            <span>Settings</span>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link {{ request()->is('admin/pricing*') ? 'active' : '' }}" href="/admin/pricing">
                                <span class="menu-icon"><i data-lucide="dollar-sign"></i></span>
                                <span class="menu-text">Pricing</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link {{ request()->is('admin/content*') ? 'active' : '' }}" href="/admin/content/terms">
                                <span class="menu-icon"><i data-lucide="file-text"></i></span>
                                <span class="menu-text">Content Pages</span>
                            </a>
                        </li>

                        <li class="menu-title">
                            <span>Account</span>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/logout">
                                <span class="menu-icon"><i data-lucide="log-out"></i></span>
                                <span class="menu-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </aside>
        <!-- End Sidebar -->

        <!-- Start Page Content here -->
        <div class="page-content">

            <!-- Topbar Start -->
            <div class="app-header min-h-topbar-height flex items-center sticky top-0 z-30 bg-(--topbar-background) border-b border-default-200">
                <div class="w-full flex items-center justify-between px-6">
                    <div class="flex items-center gap-5">
                        <!-- Sidenav Menu Toggle Button -->
                        <button id="button-toggle-menu" class="btn btn-icon size-9 bg-default-400/10 hover:bg-default-150 rounded">
                            <i class="iconify lucide--align-left text-xl"></i>
                        </button>
            
                        <!-- Topbar Search -->
                        <div class="lg:flex hidden items-center relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="iconify tabler--search text-base"></i>
                            </div>
            
                            <input type="search" id="topbar-search" class="form-input px-12 text-sm rounded border-transparent focus:border-transparent w-60" placeholder="Search something...">
            
                            <button type="button" class="absolute inset-y-0 end-0 flex items-center pe-4">
                                <span class="ms-auto font-medium">⌘ K</span>
                            </button>
                        </div>
            
                    </div>
            
                    <div class="flex items-center gap-3">
            
                        
            
                        <!-- Light/Dark Mode Button -->
                        <div class="topbar-item">
                            <button class="btn btn-icon size-8 hover:bg-default-150 transition-[scale] rounded-full" id="light-dark-mode" type="button">
                                <i class="iconify tabler--moon text-xl absolute dark:scale-0 dark:-rotate-90 scale-100 rotate-0 transition-all duration-200"></i>
                                <i class="iconify tabler--sun text-xl absolute dark:scale-100 dark:rotate-0 scale-0 rotate-90 transition-all duration-200"></i>
                            </button>
                        </div>
            
                        <!-- Notification Button -->
                        <div class="topbar-item hs-dropdown [--auto-close:inside] relative inline-flex">
                            <button type="button" class="hs-dropdown-toggle btn btn-icon size-8 hover:bg-default-150 rounded-full relative" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                <i data-lucide="bell-ring" class="size-4.5"></i>
                                <span class="absolute end-0 top-0 size-1.5 bg-primary/90 rounded-full"></span>
                            </button>
            
                            <div class="hs-dropdown-menu max-w-100 p-0" role="menu">
                                <!-- Header -->
                                <div class="p-4 border-b border-default-200">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base text-default-800">Notifications</h3>
                                        <span class="size-5 font-semibold bg-orange-500 rounded text-white flex items-center justify-center text-xs">15</span>
                                    </div>
                                </div>
            
                                <!-- Tabs -->
                                <nav class="flex gap-x-1 bg-default-150 p-2 border-b border-default-200" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                                    <button data-hs-tab="#tabsViewall" type="button" class="hs-tab-active:bg-card hs-tab-active:text-primary py-0.5 px-4 rounded font-semibold inline-flex items-center gap-x-2 border-b-2 border-transparent text-xs whitespace-nowrap text-default-500 active" aria-selected="true" aria-controls="tabsViewall" role="tab">
                                        View all
                                    </button>
                                    <button data-hs-tab="#tabsMentions" type="button" class="hs-tab-active:bg-card hs-tab-active:text-primary py-0.5 px-4 rounded font-semibold inline-flex items-center gap-x-2 border-b-2 border-transparent text-xs whitespace-nowrap text-default-500" aria-selected="false" aria-controls="tabsMentions" role="tab">
                                        Mentions
                                    </button>
                                    <button data-hs-tab="#tabsFollowers" type="button" class="hs-tab-active:bg-card hs-tab-active:text-primary py-0.5 px-4 rounded font-semibold inline-flex items-center gap-x-2 border-b-2 border-transparent text-xs whitespace-nowrap text-default-500" aria-selected="false" aria-controls="tabsFollowers" role="tab">
                                        Followers
                                    </button>
                                    <button data-hs-tab="#tabsInvites" type="button" class="hs-tab-active:bg-card hs-tab-active:text-primary py-0.5 px-4 rounded font-semibold inline-flex items-center gap-x-2 border-b-2 border-transparent text-xs whitespace-nowrap text-default-500" aria-selected="false" aria-controls="tabsInvites" role="tab">
                                        Invites
                                    </button>
                                </nav>
            
                                <!-- Tabs content -->
                                <div class="h-80" data-simplebar>
                                    <!-- View all -->
                                    <div id="tabsViewall" role="tabpanel" aria-labelledby="tabsViewall-item">
                                        <a href="#" class="flex gap-3 p-4 items-center hover:bg-default-150">
                                            <div>
                                                <div class="size-10 rounded-md  bg-default-100">
                                                    <img src="/tailwick/avatar-3-CuoB696V.png" alt="" class="rounded-md">
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between w-full text-sm">
                                                <div>
                                                    <h6 class="mb-2 font-medium text-default-800"><b>@willie_passem</b> followed you</h6>
                                                    <p class="flex items-center gap-1 text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i>
                                                        <span>Wednesday 03:42 PM</span>
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center  gap-2 text-xs text-default-500">
                                                        <div class="size-1.5 bg-primary rounded-full"></div>4 sec
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
            
                                        <a href="#" class="flex gap-3 p-4 items-start hover:bg-default-150">
                                            <div>
                                                <div class="size-10 rounded-md  bg-warning/10">
                                                    <img src="/tailwick/avatar-5-ACaGxkSo.png" alt="" class="rounded-md">
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between w-full">
                                                <div class="text-sm">
                                                    <h6 class="mb-2 font-medium text-default-800"><b>@caroline_jessica</b> commented <br>on your post</h6>
                                                    <p class="flex items-center gap-1 text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i>
                                                        <span>Wednesday 03:42 PM</span>
                                                    </p>
            
                                                    <p class="p-2  bg-default-50 text-default-500 mt-2 rounded">
                                                        Amazing! Fast, to the point, professional and really amazing to work
                                                        with them!!!
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center gap-2 text-xs text-default-500">
                                                        <div>
                                                            <div class="size-1.5 bg-primary rounded-full"></div>
                                                        </div>15 min
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
            
                                        <a href="#" class="flex gap-3 p-4 items-start hover:bg-default-150">
                                            <div>
                                                <div class="size-10 rounded-md  bg-red-100 flex justify-center items-center">
                                                    <i data-lucide="shopping-bag" class="size-5 text-danger"></i>
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between gap-2 w-full">
                                                <div>
                                                    <h6 class="mb-1 font-medium text-default-800 text-sm">Successfully purchased a business plan for
                                                        <span class="text-danger">$199.99</span>
                                                    </h6>
                                                    <p class="flex items-center gap-1 text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i>
                                                        <span>Monday 11:26 AM</span>
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center  gap-2 text-xs text-default-500">
                                                        <div class="size-1.5 bg-primary rounded-full"></div>yesterday
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
            
                                        <a href="#" class="flex gap-3 p-4 items-center hover:bg-default-150">
                                            <div class="relative">
                                                <div class="size-10 rounded-md  bg-pink-100">
                                                    <img src="/tailwick/avatar-7-QY-kCwjM.png" alt="" class="rounded-md">
                                                </div>
                                                <div class="absolute text-orange-500 bottom-0 -end-0.5 text-base">
                                                    <i data-lucide="heart" class="size-3.5 fill-orange-500"></i>
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between w-full">
                                                <div>
                                                    <h6 class="mb-1 font-medium text-default-800  text-sm"><b>@scott</b> liked your post</h6>
                                                    <p class="flex gap-1 items-center text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i><span>Thursday 06:59 AM</span>
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center gap-2 text-xs text-default-500">
                                                        <div class="size-1.5 bg-primary rounded-full"></div>1 Week
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
            
                                    <!-- Mentions -->
                                    <div id="tabsMentions" class="hidden" role="tabpanel" aria-labelledby="tabsMentions-item">
                                        <a href="#" class="flex gap-3 p-4 items-start hover:bg-default-150">
                                            <div>
                                                <div class="size-10 rounded-md  bg-warning/10">
                                                    <img src="/tailwick/avatar-5-ACaGxkSo.png" alt="" class="rounded-md">
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between w-full">
                                                <div class="text-sm">
                                                    <h6 class="mb-2 font-medium text-default-800"><b>@caroline_jessica</b> commented <br>on your post</h6>
                                                    <p class="flex items-center gap-1 text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i>
                                                        <span>Wednesday 03:42 PM</span>
                                                    </p>
            
                                                    <p class="p-2  bg-default-50 text-default-500 mt-2 rounded">
                                                        Amazing! Fast, to the point, professional and really amazing to work
                                                        with them!!!
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center gap-2 text-xs text-default-500">
                                                        <div>
                                                            <div class="size-1.5 bg-primary rounded-full"></div>
                                                        </div>15 min
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
            
                                        <a href="#" class="flex gap-3 p-4 items-center hover:bg-default-150">
                                            <div class="relative">
                                                <div class="size-10 rounded-md  bg-pink-100">
                                                    <img src="/tailwick/avatar-7-QY-kCwjM.png" alt="" class="rounded-md">
                                                </div>
                                                <div class="absolute text-orange-500 bottom-0 -end-0.5 text-base">
                                                    <i data-lucide="heart" class="size-3.5 fill-orange-500"></i>
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between w-full">
                                                <div>
                                                    <h6 class="mb-1 font-medium text-default-800  text-sm"><b>@scott</b> liked your post</h6>
                                                    <p class="flex gap-1 items-center text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i><span>Thursday 06:59 AM</span>
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center gap-2 text-xs text-default-500">
                                                        <div class="size-1.5 bg-primary rounded-full"></div>1 Week
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
            
                                    <!-- Followers -->
                                    <div id="tabsFollowers" class="hidden" role="tabpanel" aria-labelledby="tabsFollowers-item">
                                        <a href="#" class="flex gap-3 p-4 items-center hover:bg-default-150">
                                            <div>
                                                <div class="size-10 rounded-md  bg-default-100">
                                                    <img src="/tailwick/avatar-3-CuoB696V.png" alt="" class="rounded-md">
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between w-full text-sm">
                                                <div>
                                                    <h6 class="mb-2 font-medium text-default-800"><b>@willie_passem</b> followed you</h6>
                                                    <p class="flex items-center gap-1 text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i>
                                                        <span>Wednesday 03:42 PM</span>
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center  gap-2 text-xs text-default-500">
                                                        <div class="size-1.5 bg-primary rounded-full"></div>4 sec
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
            
                                    <!-- Invites -->
                                    <div id="tabsInvites" class="hidden" role="tabpanel" aria-labelledby="tabsInvites-item">
                                        <a href="#" class="flex gap-3 p-4 items-start hover:bg-default-150">
                                            <div>
                                                <div class="size-10 rounded-md  bg-red-100 flex justify-center items-center">
                                                    <i data-lucide="shopping-bag" class="size-5 text-danger"></i>
                                                </div>
                                            </div>
            
                                            <div class="flex justify-between gap-2 w-full">
                                                <div>
                                                    <h6 class="mb-1 font-medium text-default-800 text-sm">Successfully purchased a business plan for
                                                        <span class="text-danger">$199.99</span>
                                                    </h6>
                                                    <p class="flex items-center gap-1 text-default-500 text-xs">
                                                        <i data-lucide="clock" class="align-middle size-3.5"></i>
                                                        <span>Monday 11:26 AM</span>
                                                    </p>
                                                </div>
            
                                                <div>
                                                    <div class="flex items-center  gap-2 text-xs text-default-500">
                                                        <div class="size-1.5 bg-primary rounded-full"></div>yesterday
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
            
                                <!-- Footer -->
                                <div class="flex items-center justify-between p-4 border-t border-default-200">
                                    <a href="#!" class="text-sm font-medium text-default-900">Manage Notification</a>
                                    <button type="button" class="btn btn-sm text-white bg-primary">
                                        View All
                                        <i data-lucide="move-right" class="size-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
            
                        <!-- Setting Offcanvas Button -->
                        <div class="topbar-item">
                            <button class="btn btn-icon size-8 hover:bg-default-150 rounded-full" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="theme-customization" data-hs-overlay="#theme-customization">
                                <i data-lucide="settings" class="size-4.5"></i>
                            </button>
                        </div>
            
                        <!-- Profile Dropdown Button -->
                        <div class="topbar-item hs-dropdown relative inline-flex">
                            <button class="cursor-pointer rounded-full" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                <div class="hs-dropdown-toggle size-9.5 flex items-center justify-center rounded-full bg-primary text-white font-semibold text-sm">
                                    {{ strtoupper(substr($admin->full_name ?? 'A', 0, 1)) }}
                                </div>
                            </button>

                            <div class="hs-dropdown-menu min-w-48" role="menu" aria-orientation="vertical">
                                <div class="p-2">
                                    <h6 class="mb-2 text-default-500">Welcome to Allincase</h6>

                                    <div class="flex gap-3">
                                        <div class="relative inline-block">
                                            <div class="size-12 flex items-center justify-center rounded bg-primary/10 text-primary font-bold text-lg">
                                                {{ strtoupper(substr($admin->full_name ?? 'A', 0, 1)) }}
                                            </div>
                                            <span class="-top-1 -end-1 absolute size-2.5 bg-green-400 border-2 border-white rounded-full"></span>
                                        </div>

                                        <div>
                                            <h6 class="mb-1 text-sm font-semibold text-default-800">{{ $admin->full_name ?? 'Admin' }}</h6>
                                            <p class="text-default-500 text-xs">Administrator</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-t-default-200 -mx-2 my-2"></div>

                                <div class="flex flex-col gap-y-1">
                                    <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="/admin/dashboard">
                                        <i data-lucide="monitor-dot" class="size-4"></i>
                                        Dashboard
                                    </a>

                                    <div class="border-t border-default-200 -mx-2 my-1"></div>

                                    <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="/admin/logout">
                                        <i data-lucide="log-out" class="size-4"></i>
                                        Sign Out
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Topbar End -->

            <main class="p-6">
                @yield('content')
            </main>

            <!-- Footer Start -->
            <footer class="mt-auto footer flex items-center py-5 border-t border-default-200">
                <div class="lg:px-8 px-6 w-full flex md:justify-between justify-center gap-4">
                    <div>
                        <script>document.write(new Date().getFullYear())</script> © Allincase
                    </div>
                    <div class="md:flex hidden gap-2 item-center md:justify-end">
                        Design &amp; Develop by <a href="#" class="text-primary">Allincase Team</a>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->
        </div>
        <!-- End Page content -->
    </div>

    <script type="module" crossorigin src="/tailwick/app-BxTRRtUp.js"></script>
    @yield('scripts')

</body>
</html>