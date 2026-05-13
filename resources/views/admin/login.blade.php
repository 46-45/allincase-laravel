<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login | Allincase Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/tailwick/favicon-CK1QI2Xs.ico">

    <script>
        (function () {
            const html = document.documentElement;
            const storageKey = "__TAILWICK_CONFIG__";
            const savedConfig = sessionStorage.getItem(storageKey);
            const defaultConfig = { dir: "ltr", theme: "light", sidenav: { color: "light", size: "default" } };
            function getSystemTheme() { return window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light"; }
            const htmlConfig = {
                dir: html.getAttribute("dir") || defaultConfig.dir,
                theme: html.getAttribute("data-theme") === 'system' ? getSystemTheme() : html.getAttribute("data-theme") || defaultConfig.theme,
                sidenav: { color: html.getAttribute("data-sidenav-color") || defaultConfig.sidenav.color, size: html.getAttribute("data-sidenav-size") || defaultConfig.sidenav.size }
            };
            window.defaultConfig = structuredClone(htmlConfig);
            let config = savedConfig ? JSON.parse(savedConfig) : htmlConfig;
            window.config = config;
            html.setAttribute("dir", config.dir);
            html.setAttribute("data-theme", config.theme);
        })();
    </script>

    <link rel="stylesheet" crossorigin href="/tailwick/app-0ZOPNGSF.css">
</head>

<body>

    <div class="relative flex flex-row w-full overflow-hidden bg-gradient-to-r from-blue-900 h-screen to-blue-800 dark:to-blue-900 dark:from-blue-950">
        <div class="absolute inset-0 opacity-20">
            <img src="/tailwick/modern-DinTW2JS.svg" alt="">
        </div>

        <div class="mx-4 m-4 w-160 py-14 px-10 bg-card flex justify-center rounded-md text-center relative z-10">
            <div class="flex flex-col h-full w-full">

                <div class="my-auto">
                    <div class="mt-10">
                        <!-- Logo -->
                        <h2 class="text-2xl font-bold text-default-900 mb-2">All<span class="text-primary">in</span>case</h2>
                        <p class="text-default-500 text-sm mb-10">Admin Panel Login</p>

                        <!-- Form -->
                        <div class="w-100 mx-auto">
                            <form method="POST" action="/admin/login" class="text-left w-full">
                                @csrf

                                @if($error)
                                <div class="mb-4 p-3 bg-danger/10 border border-danger/20 rounded text-danger text-sm flex items-center gap-2">
                                    <i data-lucide="alert-circle" class="size-4"></i>
                                    {{ $error }}
                                </div>
                                @endif

                                <div class="mb-4">
                                    <label for="email" class="block font-medium text-default-900 text-sm mb-2">Email</label>
                                    <input type="email" id="email" name="email" class="form-input" placeholder="admin@allincase.id" required autofocus>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="block font-medium text-default-900 text-sm mb-2">Password</label>
                                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter Password" required>
                                </div>

                                <div class="flex items-center gap-2 mb-4">
                                    <input id="remember" name="remember" type="checkbox" class="form-checkbox" value="1">
                                    <label class="text-default-900 text-sm font-medium" for="remember">Remember Me</label>
                                </div>

                                <div class="mt-10 text-center">
                                    <button type="submit" class="btn bg-primary text-white w-full">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <span class="text-sm text-default-500">
                        <i class="iconify lucide--copyright align-middle"></i>
                        <script>document.write(new Date().getFullYear())</script> Allincase. Crafted with
                        <i class="iconify tabler--heart-filled align-middle text-danger"></i>
                        by
                        <span class="text-default-800">Allincase Team</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Side Illustration -->
        <div class="hidden lg:flex flex-1 items-center justify-center relative z-10">
            <div class="text-center text-white">
                <h1 class="text-4xl font-bold mb-4">Welcome Back</h1>
                <p class="text-lg text-blue-200 max-w-md">Manage your legal platform with ease. Access lawyers, clients, cases, and more from one dashboard.</p>
            </div>
        </div>
    </div>

    <script type="module" crossorigin src="/tailwick/app-BxTRRtUp.js"></script>

</body>
</html>
