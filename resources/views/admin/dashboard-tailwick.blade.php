<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard | Allincase Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Themesdesign" name="author">
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="/images/logo2.png">

    <script>
        (function () {
            const html = document.documentElement;
            const storageKey = "__TAILWICK_CONFIG__";
            const savedConfig = sessionStorage.getItem(storageKey);
    
            // Default config
            const defaultConfig = {
                dir: "ltr",
                theme: "light",
                sidenav: {
                    color: "light",
                    size: "default",
                },
            };
    
            // Build config from HTML attributes
            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light";
            }
    
            // Build config from HTML attributes
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
    
            // Save merged config as defaults globally
            window.defaultConfig = structuredClone(htmlConfig);
    
            // Load from session if exists
            let config = savedConfig ? JSON.parse(savedConfig) : htmlConfig;
            window.config = config;
    
            // Apply layout attributes immediately
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
    

    
  <script type="module" crossorigin src="/tailwick/dashboards-hr-Bod-8pvu.js"></script>
  <link rel="modulepreload" crossorigin href="/tailwick/app-BxTRRtUp.js">
  <link rel="modulepreload" crossorigin href="/tailwick/apexcharts.esm-DPbJ6jlt.js">
  <link rel="stylesheet" crossorigin href="/tailwick/app-0ZOPNGSF.css">
</head>

<body>

    <div class="wrapper">

        <!-- Start Sidebar -->
        <aside id="app-menu" class="app-menu">

            <!-- Sidenav Menu Brand Logo -->
            <a href="/admin/dashboard" class="logo-box sticky top-0 flex min-h-topbar-height items-center justify-start px-6 backdrop-blur-xs">
                <div class="logo-light">
                    <img src="/images/logo1.png" class="logo-lg h-8" alt="Allincase">
                    <img src="/images/logo2.png" class="logo-sm h-6" alt="Allincase">
                </div>
                <div class="logo-dark">
                    <img src="/images/logo1.png" class="logo-lg h-8" alt="Allincase">
                    <img src="/images/logo2.png" class="logo-sm h-6" alt="Allincase">
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
                            <a class="menu-link active" href="/admin/dashboard">
                                <span class="menu-icon"><i data-lucide="monitor-dot"></i></span>
                                <span class="menu-text">Dashboard</span>
                            </a>
                        </li>

                        <li class="menu-title">
                            <span>Management</span>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/lawyers">
                                <span class="menu-icon"><i data-lucide="users"></i></span>
                                <span class="menu-text">Lawyers</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/clients">
                                <span class="menu-icon"><i data-lucide="user"></i></span>
                                <span class="menu-text">Clients</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/cases">
                                <span class="menu-icon"><i data-lucide="briefcase"></i></span>
                                <span class="menu-text">Cases</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/categories">
                                <span class="menu-icon"><i data-lucide="folder"></i></span>
                                <span class="menu-text">Categories</span>
                            </a>
                        </li>

                        <li class="menu-title">
                            <span>Settings</span>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/pricing">
                                <span class="menu-icon"><i data-lucide="dollar-sign"></i></span>
                                <span class="menu-text">Pricing</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/content/terms">
                                <span class="menu-icon"><i data-lucide="file-text"></i></span>
                                <span class="menu-text">Content Pages</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="/admin/notifications">
                                <span class="menu-icon"><i data-lucide="bell"></i></span>
                                <span class="menu-text">Push Notification</span>
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
                        <!-- Notification Button -->
                        <div class="topbar-item hs-dropdown [--auto-close:inside] relative inline-flex">
                            @php
                                $recentActivities = collect();
                                $newClients = \App\Models\User::where('role', 'client')->where('created_at', '>=', now()->subDays(7))->orderByDesc('created_at')->limit(5)->get();
                                foreach ($newClients as $c) { $recentActivities->push(['icon' => 'user-plus', 'color' => 'text-info', 'bg' => 'bg-info/10', 'title' => $c->full_name . ' mendaftar', 'time' => $c->created_at]); }
                                $matchedCases = \App\Models\LegalCase::where('status', 'matched')->where('updated_at', '>=', now()->subDays(7))->orderByDesc('updated_at')->limit(5)->get();
                                foreach ($matchedCases as $mc) { $recentActivities->push(['icon' => 'handshake', 'color' => 'text-primary', 'bg' => 'bg-primary/10', 'title' => $mc->case_number . ' matched', 'time' => $mc->updated_at]); }
                                $paidCases = \App\Models\LegalCase::whereIn('status', ['paid', 'in_progress'])->whereNotNull('paid_at')->where('paid_at', '>=', now()->subDays(7))->orderByDesc('paid_at')->limit(5)->get();
                                foreach ($paidCases as $pc) { $recentActivities->push(['icon' => 'credit-card', 'color' => 'text-success', 'bg' => 'bg-success/10', 'title' => $pc->case_number . ' dibayar', 'time' => $pc->paid_at]); }
                                $completedCases = \App\Models\LegalCase::where('status', 'completed')->where('completed_at', '>=', now()->subDays(7))->orderByDesc('completed_at')->limit(5)->get();
                                foreach ($completedCases as $cc) { $recentActivities->push(['icon' => 'check-circle-2', 'color' => 'text-success', 'bg' => 'bg-success/10', 'title' => $cc->case_number . ' selesai', 'time' => $cc->completed_at]); }
                                $recentActivities = $recentActivities->sortByDesc('time')->take(10);
                            @endphp
                            <button type="button" class="hs-dropdown-toggle btn btn-icon size-8 hover:bg-default-150 rounded-full relative" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                <i data-lucide="bell-ring" class="size-4.5"></i>
                                @if($recentActivities->count() > 0)
                                <span class="absolute end-0 top-0 size-4 font-semibold bg-primary rounded-full text-white flex items-center justify-center text-[9px]">{{ $recentActivities->count() }}</span>
                                @endif
                            </button>
                            <div class="hs-dropdown-menu max-w-80 p-0" role="menu">
                                <div class="p-3 border-b border-default-200">
                                    <h3 class="text-sm font-semibold text-default-800">Aktivitas Terbaru</h3>
                                    <p class="text-xs text-default-500">7 hari terakhir</p>
                                </div>
                                <div class="max-h-72 overflow-y-auto" data-simplebar>
                                    @forelse($recentActivities as $activity)
                                    <div class="flex gap-3 p-3 items-center hover:bg-default-50 border-b border-default-100">
                                        <div class="size-8 rounded-md flex items-center justify-center {{ $activity['bg'] }}">
                                            <i data-lucide="{{ $activity['icon'] }}" class="size-3.5 {{ $activity['color'] }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-default-800 truncate">{{ $activity['title'] }}</p>
                                            <p class="text-[10px] text-default-400">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="p-6 text-center">
                                        <p class="text-xs text-default-500">Tidak ada aktivitas baru</p>
                                    </div>
                                    @endforelse
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
                                <img src="/images/logo2.png" alt="Profile" class="hs-dropdown-toggle size-9.5 rounded-full object-cover">
                            </button>

                            <div class="hs-dropdown-menu min-w-48" role="menu" aria-orientation="vertical">
                                <div class="p-2">
                                    <h6 class="mb-2 text-default-500">Welcome to Allincase</h6>

                                    <div class="flex gap-3">
                                        <div class="relative inline-block">
                                            <img src="/images/logo2.png" alt="" class="size-12 rounded object-cover">
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
            <main>

                <!-- Page Title Start -->
                <div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
                    <h4 class="text-default-900 text-lg font-semibold">Dashboard</h4>
                
                    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
                        <a href="#" class="text-sm font-medium text-default-700">Allincase</a>
                
                        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
                
                        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Dashboard</a>
                    </div>
                </div>
                <!-- Page Title End -->
                <div class="grid lg:grid-cols-3 grid-cols-1 mb-5 gap-5">
                    <div class="lg:col-span-2 lg:w-200">
                        <h5 class="mb-2 text-xl text-default-800 font-semibold">Welcome {{ $admin->full_name }} 🎉</h5>
                        <p class="text-default-600">
                            Allincase Admin Dashboard. Kelola lawyers, clients, cases, dan pembayaran dari sini.
                        </p>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="card">
                            <div class="card-body">
                                <div class="grid grid-cols-3">
                                    <div class="px-4 text-center border-e border-default-200 dark:border-white/14 text-sm">
                                        <h6 class="mb-1 font-bold">
                                            <span class="counter-value text-default-800" data-target="{{ $stats['pending_cases'] }}">{{ $stats['pending_cases'] }}</span>
                                        </h6>
                                        <p class="text-default-500">Pending</p>
                                    </div>

                                    <div class="px-4 text-center border-e border-default-200 dark:border-white/14 text-sm">
                                        <h6 class="mb-1 font-bold">
                                            <span class="counter-value text-default-800" data-target="{{ $stats['completed_cases'] }}">{{ $stats['completed_cases'] }}</span>
                                        </h6>
                                        <p class="text-default-500">Completed</p>
                                    </div>

                                    <div class="px-4 text-center text-sm">
                                        <h6 class="mb-1 font-bold">
                                            <span class="counter-value text-default-800" data-target="{{ $stats['pending_disbursements'] }}">{{ $stats['pending_disbursements'] }}</span>
                                        </h6>
                                        <p class="text-default-500">Disbursements</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                    <div class="col-span-1">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="grid grid-cols-4">
                                        <div class="col-span-3">
                                            <p class="text-base text-default-500 font-medium">Total Clients</p>
                                            <h5 class="text-3xl font-medium mt-4"><span class="counter-value" data-target="{{ $stats['total_clients'] }}">{{ $stats['total_clients'] }}</span></h5>
                                        </div>

                                        <div>
                                            <div id="totalEmployee"></div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mt-10">
                                        <p class="font-medium text-sm text-default-600">
                                            <span class="font-medium text-success">Allincase</span> Clients
                                        </p>
                                        <p class="font-semibold text-base text-default-400">All Time</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="grid grid-cols-4">
                                        <div class="col-span-3">
                                            <p class="text-base text-default-500 font-medium">Total Lawyers</p>
                                            <h5 class="text-3xl font-medium mt-4"><span class="counter-value" data-target="{{ $stats['total_lawyers'] }}">{{ $stats['total_lawyers'] }}</span></h5>
                                        </div>
                                        <div id="totalApplication"></div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mt-10">
                                        <p class="font-medium text-sm text-default-600">
                                            <span class="font-medium text-success">Allincase</span> Lawyers
                                        </p>
                                        <p class="font-semibold text-base text-default-400">All Time</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="grid grid-cols-4">
                                        <div class="col-span-3">
                                            <p class="text-base text-default-500 font-medium">Total Cases</p>
                                            <h5 class="text-3xl font-medium mt-4"><span class="counter-value" data-target="{{ $stats['total_cases'] }}">{{ $stats['total_cases'] }}</span></h5>
                                        </div>
                                        <div id="hiredCandidates"></div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mt-10">
                                        <p class="font-medium text-sm text-default-600">
                                            <span class="font-medium text-danger">Allincase</span> Cases
                                        </p>
                                        <p class="font-semibold text-base text-default-400">All Time</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="grid grid-cols-4">
                                        <div class="col-span-3">
                                            <p class="text-base text-default-500 font-medium">Total Revenue</p>
                                            <h5 class="text-3xl font-medium mt-4">Rp <span class="counter-value" data-target="{{ $stats['total_revenue'] }}">{{ number_format($stats['total_revenue']) }}</span></h5>
                                        </div>
                                        <div id="rejectedCandidates"></div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mt-10">
                                        <p class="font-medium text-sm text-default-600">
                                            <span class="font-medium text-danger">Allincase</span> Revenue
                                        </p>
                                        <p class="font-semibold text-base text-default-400">All Time</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="card">
                            <div class="card-body">
                                <div class="flex justify-between items-center">
                                    <h6 class="card-title">Pendapatan Bulanan</h6>

                                    <select id="yearFilter" class="form-input form-input-sm w-auto" onchange="window.location.href='/admin/dashboard?year='+this.value">
                                        @for($y = $currentYear; $y >= $currentYear - 2; $y--)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div id="revenueBarChart"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

            <!-- Footer Start -->
            <footer class="mt-auto footer flex items-center py-5 border-t border-default-200">
                <div class="lg:px-8 px-6 w-full flex md:justify-between justify-center gap-4">
                    <div>
                        <script>document.write(new Date().getFullYear())</script> © Tailwick
                    </div>
                    <div class="md:flex hidden gap-2 item-center md:justify-end">
                        Design &amp; Develop by<a href="https://themesdesign.in/" target="_blank" class="text-primary">Themesdesign</a>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->
        </div>
        <!-- End Page content -->
    </div>

    <!-- Theme Settings Offcanvas -->
    <div>
        <div id="theme-customization" class="hs-overlay hs-overlay-open:translate-x-0 hidden bg-card dark:bg-default-100 hs-overlay-open:flex flex-col translate-x-full rtl:-translate-x-full fixed inset-y-0 end-0 bottom-0 transition-all duration-300 transform max-w-sm w-full z-80 overflow-hidden">
            <div class="min-h-16 flex items-center text-default-600 border-b border-dashed border-default-900/10 px-6 gap-3">
                <h5 class="text-base grow">Theme Settings</h5>
    
                <button class="btn size-9 rounded-full btn-sm hover:bg-default-150 group" id="fullscreenBtn" data-toggle="fullscreen" aria-label="Full Screen">
                    <i class="iconify lucide--fullscreen size-5 group-[.fullscreen-active]:hidden"></i>
                    <i class="iconify lucide--minimize size-5 hidden group-[.fullscreen-active]:inline-block"></i>
                </button>
    
                <button type="button" data-hs-overlay="#theme-customization" class="btn size-9 rounded-full btn-sm hover:bg-default-150">
                    <i class="iconify tabler--x text-xl"></i>
                </button>
            </div>
    
            <div class="h-full flex-grow overflow-y-auto" data-simplebar>
                <div class="divide-y divide-dashed divide-default-200 dark:divide-white/14">
                    <div class="p-6">
                        <h5 class="font-semibold text-sm mb-3">Sidenav View</h5>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="card-radio">
                                <input class="hidden" type="radio" name="data-sidenav-size" id="sidenav-view-default" value="default">
                                <label class="form-label" for="sidenav-view-default">
                                    <span class="flex h-16 overflow-hidden border border-default-200 rounded-md">
                                        <span class="block w-8 bg-default-100">
                                            <span class="mt-1.5 mx-1.5 block space-y-1">
                                                <span class="h-1 block rounded-sm mb-2.5 bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                            </span>
                                        </span>
                                        <span class="flex flex-col flex-auto border-s border-default-200">
                                            <span class="h-3 bg-default-100">
                                                <span class="flex items-center justify-end h-full me-1.5">
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                </span>
                                            </span>
                                            <span class="flex flex-auto border-t border-default-200 bg-default-50"></span>
                                        </span>
                                    </span>
                                </label>
                                <div class="mt-1 text-md font-medium text-center text-default-600"> Default </div>
                            </div>
    
                            <div class="card-radio">
                                <input class="hidden" type="radio" name="data-sidenav-size" id="sidenav-view-hover" value="hover">
                                <label class="form-label" for="sidenav-view-hover">
                                    <span class="flex h-16 overflow-hidden border border-default-200 rounded-md">
                                        <span class="w-3 bg-default-100">
                                            <span class="size-1.5 mt-1 mx-auto rounded-sm bg-default-300"></span>
                                            <span class="flex flex-col items-center w-full mt-1.5 space-y-1">
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                            </span>
                                        </span>
                                        <span class="flex flex-col flex-auto border-s border-default-200">
                                            <span class="h-3 bg-default-100">
                                                <span class="flex items-center justify-end h-full me-1.5">
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                </span>
                                            </span>
                                            <span class="flex flex-auto border-t border-default-200 bg-default-50"></span>
                                        </span>
                                    </span>
                                </label>
                                <div class="mt-1 text-md font-medium text-center text-default-600"> Hover </div>
                            </div>
    
                            <div class="card-radio">
                                <input class="hidden" type="radio" name="data-sidenav-size" id="sidenav-view-hover-active" value="hover-active">
                                <label class="form-label" for="sidenav-view-hover-active">
                                    <span class="flex h-16 overflow-hidden border border-default-200 rounded-md">
                                        <span class="w-8 bg-default-100">
                                            <span class="mt-1.5 mx-1.5 block space-y-1">
                                                <span class="flex mb-2.5 gap-1">
                                                    <span class="h-1 block w-full rounded-sm bg-default-300"></span>
                                                    <span class="h-1 block w-2 rounded-full bg-default-300"></span>
                                                </span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                                <span class="h-1 block rounded-sm bg-default-300"></span>
                                            </span>
                                        </span>
                                        <span class="flex flex-col flex-auto border-s border-default-200">
                                            <span class="h-3 bg-default-100">
                                                <span class="flex items-center justify-end h-full me-1.5">
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                </span>
                                            </span>
                                            <span class="flex flex-auto border-t border-default-200 bg-default-50"></span>
                                        </span>
                                    </span>
                                </label>
                                <div class="mt-1 text-md font-medium text-center text-default-600"> Hover Active </div>
                            </div>
    
                            <div class="card-radio">
                                <input class="hidden" type="radio" name="data-sidenav-size" id="sidenav-view-sm" value="sm">
                                <label class="form-label" for="sidenav-view-sm">
                                    <span class="flex h-16 overflow-hidden border border-default-200 rounded-md">
                                        <span class="w-3 bg-default-100">
                                            <span class="size-1.5 mt-1 mx-auto rounded-sm bg-default-300"></span>
                                            <span class="flex flex-col items-center w-full mt-1.5 space-y-1">
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                                <span class="size-1.5 rounded-full bg-default-300"></span>
                                            </span>
                                        </span>
                                        <span class="flex flex-col flex-auto border-s border-default-200">
                                            <span class="h-3 bg-default-100">
                                                <span class="flex items-center h-full me-1.5">
                                                    <span class="grow">
                                                        <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    </span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                </span>
                                            </span>
                                            <span class="flex flex-auto border-t border-default-200 bg-default-50"></span>
                                        </span>
                                    </span>
                                </label>
                                <div class="mt-1 text-md font-medium text-center text-default-600"> Small </div>
                            </div>
    
                            <div class="card-radio">
                                <input class="hidden" type="radio" name="data-sidenav-size" id="sidenav-view-md" value="md">
                                <label class="form-label" for="sidenav-view-md">
                                    <span class="flex h-16 overflow-hidden border border-default-200 rounded-md">
                                        <span class="w-4 bg-default-100">
                                            <span class="size-2 mt-2 mx-auto rounded-sm bg-default-300"></span>
                                            <span class="flex flex-col items-center w-full mt-2 space-y-1">
                                                <span class="size-2 rounded-sm bg-default-300"></span>
                                                <span class="size-2 rounded-sm bg-default-300"></span>
                                                <span class="size-2 rounded-sm bg-default-300"></span>
                                            </span>
                                        </span>
                                        <span class="flex flex-col flex-auto border-s border-default-200">
                                            <span class="h-3 bg-default-100">
                                                <span class="flex items-center h-full me-1.5">
                                                    <span class="grow">
                                                        <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    </span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ml-1 rounded-full bg-default-300"></span>
                                                </span>
                                            </span>
                                            <span class="flex flex-auto border-t border-default-200 bg-default-50"></span>
                                        </span>
                                    </span>
                                </label>
                                <div class="mt-1 text-md font-medium text-center text-default-600"> Compact </div>
                            </div>
    
                            <div class="card-radio">
                                <input class="hidden" type="radio" name="data-sidenav-size" id="sidenav-view-mobile" value="offcanvas">
                                <label class="form-label" for="sidenav-view-mobile">
                                    <span class="flex h-16 overflow-hidden border border-default-200 rounded-md">
                                        <span class="flex flex-col flex-auto">
                                            <span class="h-3 bg-default-100">
                                                <span class="flex items-center h-full me-1.5">
                                                    <span class="size-1.5  ms-1 rounded-sm bg-default-300"></span>
                                                    <span class="size-1 block ms-1  rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-auto rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-1 rounded-full bg-default-300"></span>
                                                </span>
                                            </span>
                                            <span class="flex flex-auto border-t border-default-200 bg-default-50"></span>
                                        </span>
                                    </span>
                                </label>
                                <div class="mt-1 text-md font-medium text-center text-default-600"> Mobile </div>
                            </div>
    
                            <div class="card-radio">
                                <input class="hidden" type="radio" name="data-sidenav-size" id="sidenav-view-hidden" value="hidden">
                                <label class="form-label" for="sidenav-view-hidden">
                                    <span class="flex h-16 overflow-hidden border border-default-200 rounded-md">
                                        <span class="flex flex-col flex-auto">
                                            <span class="h-3 bg-default-100">
                                                <span class="flex flex-auto items-center h-full me-1.5">
                                                    <span class="size-1 block ms-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-auto rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-1 rounded-full bg-default-300"></span>
                                                    <span class="size-1 block ms-1 rounded-full bg-default-300"></span>
                                                </span>
                                            </span>
                                            <span class="flex flex-auto border-t border-default-200 bg-default-50"></span>
                                        </span>
                                    </span>
                                </label>
                                <div class="mt-1 text-md font-medium text-center text-default-600"> Hidden </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="p-6">
                        <h5 class="font-semibold text-sm mb-3">Theme Mode</h5>
                        <div class="flex gap-2">
                            <div>
                                <input class="hidden" type="radio" name="data-theme" id="layout-color-light" value="light">
                                <label class="form-label btn bg-default-150" for="layout-color-light">Light</label>
                            </div>
    
                            <div>
                                <input class="hidden" type="radio" name="data-theme" id="layout-color-dark" value="dark">
                                <label class="form-label btn bg-default-150" for="layout-color-dark">Dark</label>
                            </div>
    
                            <div>
                                <input class="hidden" type="radio" name="data-theme" id="layout-color-system" value="system">
                                <label class="form-label btn bg-default-150" for="layout-color-system">System</label>
                            </div>
                        </div>
                    </div>
    
                    <div class="p-6">
                        <h5 class="font-semibold text-sm mb-3">Direction</h5>
    
                        <div class="flex gap-2">
                            <div>
                                <input class="hidden" type="radio" name="dir" id="direction-ltr" value="ltr">
                                <label class="form-label btn bg-default-150" for="direction-ltr">LTR Mode</label>
                            </div>
    
                            <div>
                                <input class="hidden" type="radio" name="dir" id="direction-rtl" value="rtl">
                                <label class="form-label btn bg-default-150" for="direction-rtl">RTL Mode</label>
                            </div>
                        </div>
                    </div>
    
                    <div class="p-6">
                        <h5 class="font-semibold text-sm mb-3">Sidenav Color</h5>
                        <div class="flex gap-2">
                            <div>
                                <input class="hidden" type="radio" name="data-sidenav-color" id="menu-color-light" value="light">
                                <label class="form-label btn bg-default-150" for="menu-color-light">Light</label>
                            </div>
    
                            <div>
                                <input class="hidden" type="radio" name="data-sidenav-color" id="menu-color-dark" value="dark">
                                <label class="form-label btn bg-default-150" for="menu-color-dark">Dark</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="p-4 flex border-t border-dashed border-default-900/10">
                <div class="flex w-full gap-4">
                    <button type="button" class="btn bg-default-150 grow" id="reset-layout">Reset</button>
                    <a href="https://1.envato.market/tailwick-tailwind" target="_blank" class="btn bg-primary text-white grow">Buy Now</a>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            name: 'Pendapatan',
            data: @json($monthlyRevenue)
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '50%',
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                    if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                    return 'Rp ' + val;
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return 'Rp ' + val.toLocaleString('id-ID');
                }
            }
        },
        colors: ['#4F46E5'],
    };

    var chart = new ApexCharts(document.querySelector("#revenueBarChart"), options);
    chart.render();
</script>

</body>

</html>