<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $pageTitle ?? 'Users' }} | Allincase Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Themesdesign" name="author">
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="/tailwick/favicon-CK1QI2Xs.ico">

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
    

    
  <script type="module" crossorigin src="/tailwick/app-BxTRRtUp.js"></script>
  <link rel="stylesheet" crossorigin href="/tailwick/app-0ZOPNGSF.css">
</head>

<body>

    <div class="wrapper">

                <!-- Start Sidebar -->
        <aside id="app-menu" class="app-menu">
            <a href="/admin/dashboard" class="logo-box sticky top-0 flex min-h-topbar-height items-center justify-start px-6 backdrop-blur-xs">
                <div class="logo-light"><span class="logo-lg text-xl font-bold text-default-800">All<span class="text-primary">in</span>case</span></div>
                <div class="logo-dark"><span class="logo-lg text-xl font-bold text-white">All<span class="text-primary">in</span>case</span></div>
            </a>
            <div class="absolute top-0 end-5 flex h-topbar items-center justify"><button id="button-hover-toggle"><i class="iconify tabler--circle size-5"></i></button></div>
            <div class="relative min-h-0 flex-grow">
                <div class="size-full" data-simplebar>
                    <ul class="side-nav p-3 hs-accordion-group">
                        <li class="menu-title"><span>Main</span></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/dashboard"><span class="menu-icon"><i data-lucide="layout-dashboard"></i></span><div class="menu-text">Dashboard</div></a></li>
                        <li class="menu-title"><span>Management</span></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/lawyers"><span class="menu-icon"><i data-lucide="user-check"></i></span><div class="menu-text">Lawyers</div></a></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/clients"><span class="menu-icon"><i data-lucide="users"></i></span><div class="menu-text">Clients</div></a></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/cases"><span class="menu-icon"><i data-lucide="briefcase"></i></span><div class="menu-text">Cases</div></a></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/categories"><span class="menu-icon"><i data-lucide="folder"></i></span><div class="menu-text">Categories</div></a></li>
                        <li class="menu-title"><span>Settings</span></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/pricing"><span class="menu-icon"><i data-lucide="dollar-sign"></i></span><div class="menu-text">Pricing</div></a></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/content/terms"><span class="menu-icon"><i data-lucide="file-text"></i></span><div class="menu-text">Content Pages</div></a></li>
                        <li class="menu-title"><span>Account</span></li>
                        <li class="menu-item"><a class="menu-link" href="/admin/logout"><span class="menu-icon"><i data-lucide="log-out"></i></span><div class="menu-text">Logout</div></a></li>
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
                            <button class="cursor-pointer bg-pink-100 rounded-full" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                <img src="/tailwick/avatar-1-DOkfBXSU.png" alt="user-image" class="hs-dropdown-toggle rounded-full size-9.5">
                            </button>
            
                            <div class="hs-dropdown-menu min-w-48" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-icons">
                                <div class="p-2">
                                    <h6 class="mb-2 text-default-500">Welcome to Tailwick</h6>
            
                                    <a href="#!" class="flex gap-3">
                                        <div class="relative inline-block">
                                            <div class="rounded bg-default-200">
                                                <img src="/tailwick/avatar-1-DOkfBXSU.png" alt="" class="size-12 rounded">
                                            </div>
                                            <span class="-top-1 -end-1 absolute size-2.5 bg-green-400 border-2 border-white rounded-full"></span>
                                        </div>
            
                                        <div>
                                            <h6 class="mb-1 text-sm font-semibold text-default-800">Paula Keenan</h6>
                                            <p class="text-default-500">CEO & Founder</p>
                                        </div>
                                    </a>
                                </div>
            
                                <div class="border-t border-t-default-200 -mx-2 my-2"></div>
            
                                <div class="flex flex-col gap-y-1">
                                    <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="apps-mailbox.html">
                                        <i data-lucide="mail" class="size-4"></i>
                                        Inbox
                                        <span class="size-4.5 font-semibold bg-danger rounded text-white flex items-center justify-center text-xs">15</span>
                                    </a>
            
                                    <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="apps-chat.html">
                                        <i data-lucide="messages-square" class="size-4"></i>
                                        Chat
                                    </a>
            
                                    <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="pages-pricing.html">
                                        <i data-lucide="gem" class="size-4"></i>
                                        Upgrade Pro
                                    </a>
            
                                    <div class="border-t border-default-200 -mx-2 my-1"></div>
            
                                    <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded" href="auth-basic-logout.html">
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
                    <h4 class="text-default-900 text-lg font-semibold">List View</h4>
                
                    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
                        <a href="#" class="text-sm font-medium text-default-700">Tailwick</a>
                
                        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
                
                        <a href="#" class="text-sm font-medium text-default-700">Users</a>
                
                        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
                
                        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">List View</a>
                    </div>
                </div>
                <!-- Page Title End -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Users List</h6>
                        <button class="btn btn-sm bg-primary text-white">
                            <i data-lucide="plus" class="size-4 me-1"></i>Add user
                        </button>
                    </div>

                    <div class="card-header">
                        <div class="md:flex items-center md:space-y-0 space-y-4 gap-3">
                            <div class="relative w-full">
                                <input type="email" class="form-input form-input-sm ps-9" placeholder="Search for name,email">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                                    <i data-lucide="search" class="size-3.5 flex items-center text-default-500 fill-default-100"></i>
                                </div>
                            </div>

                            <select class="form-input form-input-sm">
                                <option selected="">select status</option>
                                <option>Hidden</option>
                                <option>Rejected</option>
                                <option>Verified</option>
                                <option>Waiting</option>
                            </select>
                        </div>

                        <div class="flex gap-2 items-center flex-wrap">
                            <button type="button" class="btn btn-sm bg-transparent border border-dashed border-primary text-primary hover:bg-primary/10">
                                <i data-lucide="download" class="size-4"></i>
                                Import
                            </button>

                            <button type="button" class="btn btn-sm size-7.5 bg-default-100 text-default-500 hover:bg-default-1500 hover:text-white">
                                <i data-lucide="sliders-horizontal" class="size-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                                        <thead class="bg-default-150">
                                            <tr class="text-sm font-normal text-default-700 whitespace-nowrap">
                                                <th class="ps-4 text-start">
                                                    <input id="checkbox-all" type="checkbox" class="form-checkbox">
                                                </th>
                                                <th scope="col" class="px-3.5 py-3 text-start">User ID</th>
                                                <th scope="col" class="px-3.5 py-3 text-start">Name</th>
                                                <th scope="col" class="px-3.5 py-3 text-start">Location</th>
                                                <th scope="col" class="px-3.5 py-3 text-start">Email</th>
                                                <th scope="col" class="px-3.5 py-3 text-start">Phone Number</th>
                                                <th scope="col" class="px-3.5 py-3 text-start">Joining Date</th>
                                                <th scope="col" class="px-3.5 py-3 text-start">Status</th>
                                                <th scope="col" class="px-3.5 py-3 text-start">Action</th>
                                            </tr>
                                        </thead>

                                                                                                                        <tbody>
                                            @foreach($users as $user)
                                            <tr class="text-default-800 font-normal text-sm whitespace-nowrap">
                                                <td class="py-3 ps-4">
                                                    <input type="checkbox" class="form-checkbox">
                                                </td>
                                                <td class="px-3.5 py-3 text-sm text-primary">#{{ $user->id }}</td>
                                                <td class="flex py-3 px-3.5 items-center gap-3">
                                                    <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold">{{ strtoupper(substr($user->full_name, 0, 1)) }}</div>
                                                    <div>
                                                        <h6 class="mb-1.5 font-semibold"><a href="/admin/{{ $userType }}/{{ $user->id }}" class="text-default-800">{{ $user->full_name }}</a></h6>
                                                        <p class="text-default-500">{{ $user->email }}</p>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-3.5">{{ $user->phone ?? '-' }}</td>
                                                <td class="py-3 px-3.5">{{ $user->email }}</td>
                                                <td class="py-3 px-3.5">{{ $user->phone ?? '-' }}</td>
                                                <td class="py-3 px-3.5">{{ $user->created_at?->format('d M, Y') }}</td>
                                                <td class="px-3.5 py-3">
                                                    @if($user->is_active)
                                                    <span class="py-0.5 px-2.5 inline-flex items-center gap-x-1 text-xs font-medium bg-success/10 text-success rounded">
                                                        <i data-lucide="check-circle-2" class="size-3"></i> Active
                                                    </span>
                                                    @else
                                                    <span class="py-0.5 px-2.5 inline-flex items-center gap-x-1 text-xs font-medium bg-danger/10 text-danger rounded">
                                                        <i data-lucide="x-circle" class="size-3"></i> Inactive
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="px-3.5 py-3">
                                                    <div class="flex gap-2">
                                                        <a href="/admin/{{ $userType }}/{{ $user->id }}" class="btn size-7.5 bg-default-100 hover:bg-primary hover:text-white text-default-500 rounded">
                                                            <i data-lucide="eye" class="size-4"></i>
                                                        </a>
                                                        <form method="POST" action="/admin/{{ $userType }}/{{ $user->id }}/toggle-active" class="inline">
                                                            @csrf
                                                            <button type="submit" class="btn size-7.5 bg-default-100 hover:bg-warning hover:text-white text-default-500 rounded">
                                                                <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="size-4"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-default-500 text-sm">Showing <b>10</b> of <b>58</b> Results</p>
                            <nav class="flex items-center gap-2" aria-label="Pagination">
                                <button type="button" class="btn btn-sm border bg-transparent border-default-200 dark:border-white/14 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10">
                                    <i data-lucide="chevron-left" class="size-4 me-1"></i> Prev
                                </button>

                                <button type="button" class="btn size-7.5 bg-transparent border border-default-200 dark:border-white/14 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10">
                                    1
                                </button>

                                <button type="button" class="btn size-7.5 bg-primary text-white">
                                    2
                                </button>

                                <button type="button" class="btn size-7.5 bg-transparent border border-default-200 dark:border-white/14 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10">
                                    3
                                </button>

                                <button type="button" class="btn btn-sm border bg-transparent border-default-200 dark:border-white/14 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10">Next
                                    <i data-lucide="chevron-right" class="size-4 ms-1"></i>
                                </button>
                            </nav>
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
</body>

</html>



