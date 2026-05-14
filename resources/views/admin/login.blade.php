<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login | Allincase Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/images/logo2.png">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f5f5f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 540px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.15);
        }
        .login-left {
            flex: 1;
            background: #ffffff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-right {
            flex: 1;
            background: linear-gradient(135deg, #2c2c2c 0%, #1f1f1f 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }
        .logo-img { height: 44px; margin-bottom: 8px; }
        .logo-img-lg { height: 80px; margin-bottom: 16px; }
        .subtitle { color: #999; font-size: 13px; margin-bottom: 32px; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            color: #555;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
        }
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            background: #f9f9f7;
            border: 1px solid #ddd;
            border-radius: 8px;
            color: #333;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-group input:focus { border-color: #d4af37; }
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .remember-row label { color: #666; font-size: 13px; cursor: pointer; }
        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #d4af37;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #d4af37;
            color: #111;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #c49f2e; }
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .right-title {
            color: #d4af37;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .right-desc {
            color: #777;
            font-size: 14px;
            line-height: 1.6;
            text-align: center;
            max-width: 280px;
        }
        .features {
            display: flex;
            gap: 20px;
            margin-top: 32px;
        }
        .feature-item {
            text-align: center;
        }
        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: rgba(212,175,55,0.08);
            border: 1px solid rgba(212,175,55,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 6px;
        }
        .feature-icon svg {
            width: 20px; height: 20px;
            stroke: #d4af37;
            fill: none;
            stroke-width: 1.5;
        }
        .feature-label { color: #666; font-size: 11px; }
        .footer-text {
            color: #aaa;
            font-size: 11px;
            margin-top: 32px;
        }
        @media (max-width: 768px) {
            .login-container { flex-direction: column; max-width: 400px; }
            .login-right { display: none; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Left - Form -->
    <div class="login-left">
        <div style="text-align: center;">
            <img src="/images/logo1.png" alt="Allincase" class="logo-img">
            <p class="subtitle">Admin Panel</p>
        </div>

        @if($error)
        <div class="error-box">{{ $error }}</div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="admin@allincase.id" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <p class="footer-text" style="text-align: center;">&copy; {{ date('Y') }} Allincase</p>
    </div>

    <!-- Right - Branding -->
    <div class="login-right">
        <img src="/images/logo3.png" alt="Allincase" class="logo-img-lg">
        <h2 class="right-title">Welcome Back</h2>
        <p class="right-desc">Manage your legal platform with ease. Access lawyers, clients, cases, and more.</p>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <span class="feature-label">Lawyers</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <span class="feature-label">Cases</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                </div>
                <span class="feature-label">Payments</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
                </div>
                <span class="feature-label">Analytics</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
