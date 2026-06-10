<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            background-color: #0b0f17 !important;
            color: #f1f5f9 !important;
            font-family: 'Inter', sans-serif !important;
            background-image:
                radial-gradient(at 10% 20%, rgba(163, 230, 53, 0.05) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(6, 182, 212, 0.05) 0px, transparent 50%) !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em !important;
        }

        .text-glow { text-shadow: 0 0 10px rgba(163, 230, 53, 0.4) !important; }

        /* ── Sidebar Overlay ── */
        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); z-index: 998; opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .sidebar-overlay.open { opacity: 1; visibility: visible; }

        /* ── Sidebar Drawer ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: 280px; height: 100%; z-index: 999;
            background: rgba(13, 18, 30, 0.98);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255,255,255,0.08);
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex; flex-direction: column;
            box-shadow: 8px 0 40px rgba(0,0,0,0.3);
        }
        .sidebar.open { transform: translateX(0); }

        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.3rem;
            background: linear-gradient(135deg, rgba(163,230,53,0.08), rgba(16,185,129,0.04));
            border-bottom: 1px solid rgba(255,255,255,0.06);
            position: relative; overflow: hidden;
        }
        .sidebar-brand::after {
            content: ''; position: absolute; bottom: 0; left: 1.5rem; width: 40px; height: 3px;
            background: linear-gradient(135deg, #a3e635, #10b981); border-radius: 3px;
        }
        .sidebar-brand .welcome-text {
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em;
            color: #64748b; margin-bottom: 0.3rem;
        }
        .sidebar-brand .user-name {
            background: linear-gradient(135deg, #a3e635 0%, #10b981 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: -0.03em; text-shadow: 0 0 20px rgba(163,230,53,0.15);
        }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 0.5rem 0.75rem; }

        .sidebar-nav a {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.75rem 1rem; color: #94a3b8; text-decoration: none;
            font-weight: 500; font-size: 0.9rem; font-family: 'Inter', sans-serif;
            transition: all 0.2s ease; border-radius: 10px; margin-bottom: 2px;
            position: relative;
        }
        .sidebar-nav a:hover {
            color: #e2e8f0; background: rgba(255,255,255,0.04);
        }
        .sidebar-nav a.active {
            color: #a3e635; background: linear-gradient(135deg, rgba(163,230,53,0.1), rgba(16,185,129,0.05));
            font-weight: 600;
        }
        .sidebar-nav a.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 24px; background: #a3e635; border-radius: 0 3px 3px 0;
        }
        .sidebar-nav a svg { width: 19px; height: 19px; flex-shrink: 0; opacity: 0.7; }
        .sidebar-nav a.active svg, .sidebar-nav a:hover svg { opacity: 1; }

        .sidebar-footer {
            padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);
            background: rgba(0,0,0,0.15);
        }
        .sidebar-footer .logout-link {
            color: #64748b; font-size: 0.8rem; text-decoration: none;
            display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0;
            transition: color 0.15s ease;
        }
        .sidebar-footer .logout-link:hover { color: #ef4444; }

        /* ── Hamburger ── */
        .hamburger {
            background: none; border: none; cursor: pointer;
            display: flex; flex-direction: column; gap: 5px; z-index: 101;
            padding: 0.5rem;
        }
        .hamburger span {
            display: block; width: 22px; height: 2px; background: #94a3b8;
            border-radius: 2px; transition: all 0.25s ease;
        }
        .hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

        /* ── Top Bar ── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; padding: 0.65rem 1rem;
            background: rgba(9,13,22,0.88);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 1px 20px rgba(0,0,0,0.2);
        }
        .topbar-brand {
            margin-left: 0.75rem;
            font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem;
            background: linear-gradient(135deg, #a3e635, #10b981);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
        }

        /* ── Main Content ── */
        .main-content { padding: 0 1rem 2rem; }

        .content-container { width: 100%; padding: 0; }

        @media (min-width: 768px) {
            .sidebar { transform: translateX(0); }
            .sidebar-overlay { display: none; }
            .hamburger { display: none; }
            .topbar { padding-left: 296px; }
            .main-content { padding-left: 296px; padding-right: 1.5rem; padding-bottom: 2rem; }
            .content-container { padding: 1rem 0; }
        }

        /* ── Cards ── */
        .card {
            background: rgba(20, 27, 45, 0.65) !important;
            backdrop-filter: blur(16px) !important; -webkit-backdrop-filter: blur(16px) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
            overflow: hidden !important; color: #f1f5f9 !important;
        }
        .card:hover { border-color: rgba(163, 230, 53, 0.3) !important; box-shadow: 0 15px 35px rgba(163, 230, 53, 0.08) !important; }
        .card-header {
            background: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 1.25rem 1.5rem !important;
            font-family: 'Outfit', sans-serif !important; font-weight: 600 !important;
            font-size: 1.15rem !important; color: #ffffff !important; letter-spacing: -0.01em !important;
        }
        .card-body { padding: 1.5rem !important; }
        .card-footer {
            background: rgba(255, 255, 255, 0.02) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 1.25rem 1.5rem !important;
        }

        /* ── List Group ── */
        .list-group-item {
            background-color: transparent !important; color: #cbd5e1 !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            padding: 1.2rem 1.5rem !important; transition: all 0.2s ease !important;
            display: flex !important; justify-content: space-between !important; align-items: center !important;
        }
        .list-group-item-action:hover { background: rgba(255, 255, 255, 0.02) !important; color: #ffffff !important; padding-left: 1.75rem !important; }
        .list-group-item.active {
            background: linear-gradient(135deg, rgba(163, 230, 53, 0.2) 0%, rgba(16, 185, 129, 0.1) 100%) !important;
            border-left: 4px solid #a3e635 !important; color: #a3e635 !important; font-weight: 600 !important;
        }

        /* ── Buttons ── */
        .btn {
            font-family: 'Outfit', sans-serif !important; font-weight: 600 !important;
            text-transform: uppercase !important; letter-spacing: 0.05em !important;
            padding: 0.75rem 1.5rem !important; border-radius: 10px !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important; font-size: 0.85rem !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #a3e635 0%, #10b981 100%) !important;
            border: none !important; color: #0b0f17 !important;
            box-shadow: 0 4px 15px rgba(163, 230, 53, 0.3) !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            transform: translateY(-2px) !important; box-shadow: 0 8px 25px rgba(163, 230, 53, 0.5) !important;
            background: linear-gradient(135deg, #b4f34c 0%, #10b981 100%) !important; color: #0b0f17 !important;
        }
        .btn-outline-primary {
            color: #a3e635 !important; border: 2px solid #a3e635 !important; background: transparent !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background: linear-gradient(135deg, #a3e635 0%, #10b981 100%) !important;
            border-color: transparent !important; color: #0b0f17 !important;
            transform: translateY(-2px) !important; box-shadow: 0 8px 20px rgba(163, 230, 53, 0.3) !important;
        }
        .btn-secondary {
            background: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: #e2e8f0 !important;
        }
        .btn-secondary:hover { background: #334155 !important; color: #ffffff !important; }

        /* ── Forms ── */
        .form-group label {
            font-family: 'Outfit', sans-serif !important; font-weight: 500 !important;
            font-size: 0.9rem !important; color: #94a3b8 !important; margin-bottom: 0.5rem !important;
            text-transform: uppercase !important; letter-spacing: 0.05em !important;
        }
        .form-control {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important; color: #f1f5f9 !important;
            border-radius: 10px !important; padding: 0.75rem 1rem !important; height: auto !important;
            transition: all 0.2s ease !important;
        }
        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.8) !important;
            border-color: #a3e635 !important; box-shadow: 0 0 0 3px rgba(163, 230, 53, 0.25) !important;
        }
        select.form-control {
            appearance: none !important;
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important; background-position: right 1rem center !important;
            background-size: 16px 12px !important; padding-right: 2.5rem !important;
        }

        /* ── Tables ── */
        .table { background-color: transparent !important; color: #e2e8f0 !important; }
        .table th {
            font-family: 'Outfit', sans-serif !important; font-weight: 600 !important;
            text-transform: uppercase !important; font-size: 0.8rem !important; letter-spacing: 0.05em !important;
            color: #94a3b8 !important; border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
            border-top: none !important; padding: 1rem 1.25rem !important;
        }
        .table td { padding: 1.2rem 1.25rem !important; border-top: 1px solid rgba(255, 255, 255, 0.06) !important; vertical-align: middle !important; }

        /* ── Day Selector ── */
        .day-selector-grid {
            display: grid !important; grid-template-columns: repeat(7, 1fr) !important;
            gap: 0.75rem !important; margin-top: 0.5rem !important;
        }
        .day-selector-item { position: relative !important; }
        .day-selector-item input[type="checkbox"] {
            position: absolute !important; opacity: 0 !important; width: 100% !important;
            height: 100% !important; cursor: pointer !important; z-index: 2 !important; left: 0 !important; top: 0 !important;
        }
        .day-selector-item input[type="checkbox"]:checked + label {
            background: linear-gradient(135deg, rgba(163, 230, 53, 0.2) 0%, rgba(16, 185, 129, 0.15) 100%) !important;
            border-color: #a3e635 !important; color: #a3e635 !important;
            box-shadow: 0 0 15px rgba(163, 230, 53, 0.15) !important;
        }
        .day-selector-item label {
            display: block !important; text-align: center !important; padding: 0.75rem 1rem !important;
            background: rgba(30, 41, 59, 0.3) !important; border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 10px !important; color: #94a3b8 !important;
            font-family: 'Outfit', sans-serif !important; font-weight: 600 !important; font-size: 0.9rem !important;
            margin: 0 !important; transition: all 0.2s ease !important; cursor: pointer !important;
        }
        .day-selector-item label:hover { background: rgba(30, 41, 59, 0.5) !important; border-color: rgba(255, 255, 255, 0.2) !important; color: #ffffff !important; }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(163, 230, 53, 0.1) 100%) !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important; color: #34d399 !important;
            border-radius: 12px !important; font-weight: 500 !important;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.05) !important;
        }

        .hover-glow:hover { color: #a3e635 !important; text-shadow: 0 0 10px rgba(163, 230, 53, 0.3) !important; }

        /* ── Scrollbar ── */
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
    </style>
</head>
<body>
    <div id="app">
        <!-- Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- Sidebar Drawer -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                @guest
                    <div class="welcome-text">Welcome</div>
                    <div class="user-name">Guest</div>
                @else
                    <div class="welcome-text">Welcome</div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                @endguest
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('workout.index') }}" class="{{ request()->routeIs('workout.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h1m16 0h1m-9-9v1m0 16v1m-6.36-2.64l.7-.7m12.02-12.02l.7-.7M5.64 5.64l.7.7m12.02 12.02l.7.7"/></svg>
                    Workouts
                </a>
                <a href="{{ route('meal.index') }}" class="{{ request()->routeIs('meal.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
                    Meals
                </a>
                <a href="{{ route('exercise.index') }}" class="{{ request()->routeIs('exercise.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    Exercises
                </a>
                <a href="{{ route('body.index') }}" class="{{ request()->routeIs('body.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Body Section
                </a>
                <a href="{{ route('vital.index') }}" class="{{ request()->routeIs('vital.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Vitals
                </a>
            </nav>
            <div class="sidebar-footer">
                @guest
                    <a href="{{ route('login') }}" class="logout-link">Login</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="logout-link ml-2">Register</a>
                    @endif
                @else
                    <a href="{{ route('logout') }}" class="logout-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </div>
        </aside>

        <!-- Top Bar -->
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
            <span class="topbar-brand">{{ config('app.name', 'Lishe Afya') }}</span>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="alert alert-success m-0" role="alert">
                <div class="container text-center text-uppercase">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="main-content">
            <div class="p-4">
                @yield('content')
            </div>

        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hamburger = document.getElementById('hamburgerBtn');
        let touchStartX = 0;

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            hamburger.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            hamburger.classList.remove('open');
            document.body.style.overflow = '';
        }
        function toggleSidebar() {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        }

        // Swipe to open
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
        }, {passive: true});
        document.addEventListener('touchend', function(e) {
            const diff = e.changedTouches[0].clientX - touchStartX;
            if (diff > 60 && touchStartX < 30) openSidebar();
        }, {passive: true});
    </script>
</body>
</html>
