<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lishe Afya') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            background: #0b0f17 !important;
            color: #f1f5f9 !important;
            font-family: 'Inter', sans-serif !important;
            min-height: 100vh !important;
            margin: 0;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em !important;
        }

        .text-glow { text-shadow: none !important; }

        /* ── Auth Wrapper ── */
        .auth-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }

        /* ── Brand ── */
        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-brand .brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: #f1f5f9;
            letter-spacing: -0.03em;
        }
        .auth-brand .brand-tagline {
            font-size: 0.75rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 0.3rem;
            font-weight: 500;
        }

        /* ── Cards ── */
        .card {
            background: #101826 !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
            overflow: hidden !important; color: #f1f5f9 !important;
        }
        .card-header {
            background: #0c1220 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            padding: 1.25rem 1.5rem !important;
            font-family: 'Outfit', sans-serif !important; font-weight: 600 !important;
            font-size: 1.05rem !important; color: #f1f5f9 !important; letter-spacing: -0.01em !important;
        }
        .card-body { padding: 1.5rem !important; }

        /* ── Buttons ── */
        .btn {
            font-family: 'Outfit', sans-serif !important; font-weight: 600 !important;
            text-transform: uppercase !important; letter-spacing: 0.05em !important;
            padding: 0.75rem 1.5rem !important; border-radius: 10px !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important; font-size: 0.85rem !important;
        }
        .btn-primary {
            background: #a3e635 !important;
            border: none !important; color: #0b0f17 !important;
            box-shadow: 0 2px 8px rgba(163, 230, 53, 0.2) !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            transform: translateY(-2px) !important; box-shadow: 0 4px 16px rgba(163, 230, 53, 0.3) !important;
            background: #b8f54a !important; color: #0b0f17 !important;
        }
        .btn-secondary {
            background: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: #e2e8f0 !important;
        }
        .btn-secondary:hover { background: #334155 !important; color: #ffffff !important; }
        .btn-link {
            color: #a3e635 !important;
            font-family: 'Outfit', sans-serif !important; font-weight: 500 !important;
        }
        .btn-link:hover { color: #b4f34c !important; text-decoration: none !important; }

        /* ── Forms ── */
        .form-group label {
            font-family: 'Outfit', sans-serif !important; font-weight: 500 !important;
            font-size: 0.85rem !important; color: #64748b !important; margin-bottom: 0.5rem !important;
            text-transform: uppercase !important; letter-spacing: 0.05em !important;
        }
        .form-control {
            background-color: #0f172a !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important; color: #f1f5f9 !important;
            border-radius: 8px !important; padding: 0.75rem 1rem !important; height: auto !important;
            transition: all 0.2s ease !important;
        }
        .form-control:focus {
            background-color: #0f172a !important;
            border-color: #a3e635 !important; box-shadow: 0 0 0 3px rgba(163, 230, 53, 0.15) !important;
        }
        .form-control::placeholder { color: #475569 !important; }

        /* ── Custom Checkbox ── */
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #a3e635 !important;
            border-color: #a3e635 !important;
        }
        .custom-control-label { color: #94a3b8 !important; }
        .custom-control-label::before { background-color: #0f172a !important; border-color: rgba(255,255,255,0.08) !important; }

        /* ── Invalid Feedback ── */
        .invalid-feedback { color: #fb7185 !important; font-size: 0.8rem !important; }
        .is-invalid { border-color: #fb7185 !important; }

        /* ── Alerts ── */
        .alert-success {
            background: #0d2818 !important;
            border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #34d399 !important;
            border-radius: 10px !important; font-weight: 500 !important;
        }

        /* ── Auth Footer Links ── */
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        .auth-links a {
            color: #94a3b8;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .auth-links a:hover { color: #a3e635; }
        .auth-links .divider {
            color: #334155;
            margin: 0 0.75rem;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-brand">
            <div class="brand-name">{{ config('app.name', 'Lishe Afya') }}</div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

        <div class="auth-links d-flex justify-content-between">
            @if (Route::has('login') && !request()->routeIs('login'))
                <a href="{{ route('login') }}">Sign In</a>
            @else
                <span></span>
            @endif
            @if (Route::has('register') && !request()->routeIs('register'))
                <a href="{{ route('register') }}">Create Account</a>
            @else
                <span></span>
            @endif
        </div>
    </div>
</body>
</html>
