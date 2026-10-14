<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Case Management') | Jajal Medical Implants</title>

    <!-- Immediate Theme Application Script (Avoids Flicker) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('jm-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root, [data-theme="light"], [data-bs-theme="light"] {
            --jm-primary: #0f172a;
            --jm-primary-dark: #020617;
            --jm-accent: #2563eb;
            --jm-accent-hover: #1d4ed8;
            --jm-teal: #0d9488;
            --jm-emerald: #059669;
            --jm-amber: #d97706;
            --jm-rose: #e11d48;
            --jm-bg: #f8fafc;
            --jm-card-bg: #ffffff;
            --jm-card-header: #ffffff;
            --jm-border: #e2e8f0;
            --jm-border-subtle: #f1f5f9;
            --jm-text-main: #0f172a;
            --jm-text-secondary: #334155;
            --jm-text-muted: #64748b;
            --jm-input-bg: #ffffff;
            --jm-input-border: #cbd5e1;
            --jm-table-header-bg: #f8fafc;
            --jm-table-hover: #f8fafc;
            --jm-nav-bg: linear-gradient(180deg, #0f172a 0%, #0a0f1d 100%);
            --jm-footer-bg: #ffffff;
        }

        [data-theme="dark"], [data-bs-theme="dark"] {
            --jm-primary: #38bdf8;
            --jm-primary-dark: #0284c7;
            --jm-accent: #3b82f6;
            --jm-accent-hover: #60a5fa;
            --jm-teal: #14b8a6;
            --jm-emerald: #10b981;
            --jm-amber: #f59e0b;
            --jm-rose: #f43f5e;
            --jm-bg: #0b1120;
            --jm-card-bg: #151e32;
            --jm-card-header: #151e32;
            --jm-border: #22304d;
            --jm-border-subtle: #1c273e;
            --jm-text-main: #f8fafc;
            --jm-text-secondary: #cbd5e1;
            --jm-text-muted: #94a3b8;
            --jm-input-bg: #0f172a;
            --jm-input-border: #2a3a5e;
            --jm-table-header-bg: #0f172a;
            --jm-table-hover: #1c273e;
            --jm-nav-bg: linear-gradient(180deg, #060a13 0%, #0b1120 100%);
            --jm-footer-bg: #0d1527;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--jm-bg);
            color: var(--jm-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Top Bar & Navbar */
        .navbar-medical {
            background: var(--jm-nav-bg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.25);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.03em;
            color: #ffffff !important;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
        }

        .brand-logo-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.4);
        }

        .nav-pill-link {
            color: #94a3b8 !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.55rem 0.95rem !important;
            border-radius: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .nav-pill-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .nav-pill-link.active {
            color: #ffffff !important;
            background-color: rgba(37, 99, 235, 0.25);
            border: 1px solid rgba(59, 130, 246, 0.35);
            font-weight: 600;
        }

        /* User Profile Pill */
        .user-dropdown-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            padding: 0.4rem 0.85rem;
            border-radius: 30px;
            transition: all 0.2s;
        }

        .user-dropdown-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .user-avatar-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Role Badges */
        .role-pill {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .role-pill-admin {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .role-pill-viewer {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .role-pill-engineer {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        [data-theme="dark"] .role-pill-admin {
            background-color: #450a0a;
            color: #fca5a5;
            border: 1px solid #7f1d1d;
        }
        [data-theme="dark"] .role-pill-viewer {
            background-color: #082f49;
            color: #7dd3fc;
            border: 1px solid #0369a1;
        }
        [data-theme="dark"] .role-pill-engineer {
            background-color: #451a03;
            color: #fcd34d;
            border: 1px solid #78350f;
        }

        /* Status Pills with Glowing Dot */
        .status-pill {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
        }

        .status-pill .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-draft { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .status-draft .dot { background: #94a3b8; }

        .status-planning { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-planning .dot { background: #3b82f6; box-shadow: 0 0 6px #60a5fa; }

        .status-in-review { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .status-in-review .dot { background: #f59e0b; box-shadow: 0 0 6px #fbbf24; }

        .status-approved { background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; }
        .status-approved .dot { background: #14b8a6; box-shadow: 0 0 6px #2dd4bf; }

        .status-completed { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .status-completed .dot { background: #10b981; box-shadow: 0 0 6px #34d399; }

        .status-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .status-cancelled .dot { background: #ef4444; }

        /* Dark Status Pills */
        [data-theme="dark"] .status-draft { background: #1e293b; color: #94a3b8; border-color: #334155; }
        [data-theme="dark"] .status-planning { background: #172554; color: #93c5fd; border-color: #1e40af; }
        [data-theme="dark"] .status-in-review { background: #451a03; color: #fde047; border-color: #854d0e; }
        [data-theme="dark"] .status-approved { background: #042f2e; color: #5eead4; border-color: #115e59; }
        [data-theme="dark"] .status-completed { background: #064e3b; color: #6ee7b7; border-color: #047857; }
        [data-theme="dark"] .status-cancelled { background: #450a0a; color: #fca5a5; border-color: #991b1b; }

        /* Priority Badges */
        .priority-badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .priority-low { background: #f1f5f9; color: #64748b; }
        .priority-medium { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
        .priority-high { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }

        [data-theme="dark"] .priority-low { background: #1e293b; color: #94a3b8; }
        [data-theme="dark"] .priority-medium { background: #431407; color: #fdba74; border-color: #7c2d12; }
        [data-theme="dark"] .priority-high { background: #450a0a; color: #fca5a5; border-color: #991b1b; }

        /* Card Elevations & Surface */
        .card-clean {
            background: var(--jm-card-bg);
            border: 1px solid var(--jm-border);
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .card-stat {
            background: var(--jm-card-bg);
            border: 1px solid var(--jm-border);
            border-radius: 16px;
            padding: 1.25rem 1.35rem;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.03);
            position: relative;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.12);
            border-color: var(--jm-accent);
        }

        .card-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        [data-theme="dark"] .btn-outline-danger {
            color: #fca5a5;
            border-color: #7f1d1d;
        }
        [data-theme="dark"] .btn-outline-danger:hover {
            background-color: #450a0a;
            color: #fecaca;
        }
        [data-theme="dark"] .btn-outline-primary {
            color: #93c5fd;
            border-color: #1e40af;
        }
        [data-theme="dark"] .btn-outline-primary:hover {
            background-color: #1e3a8a;
            color: #ffffff;
        }
        [data-theme="dark"] .btn-outline-warning {
            color: #fde047;
            border-color: #854d0e;
        }
        [data-theme="dark"] .btn-outline-warning:hover {
            background-color: #451a03;
            color: #fef08a;
        }
        [data-theme="dark"] .alert-warning {
            background-color: #451a03 !important;
            color: #fde047 !important;
            border-color: #854d0e !important;
        }
        [data-theme="dark"] .alert-warning strong,
        [data-theme="dark"] .alert-warning h6 {
            color: #fef08a !important;
        }

        /* Buttons */
        .btn-jm-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.55rem 1.15rem;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
            transition: all 0.2s;
        }

        .btn-jm-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .btn-jm-secondary {
            background: var(--jm-card-bg);
            border: 1px solid var(--jm-border);
            color: var(--jm-text-secondary);
            font-weight: 600;
            padding: 0.55rem 1.15rem;
            border-radius: 10px;
            transition: all 0.15s ease;
        }

        .btn-jm-secondary:hover {
            background: var(--jm-table-hover);
            border-color: #cbd5e1;
            color: var(--jm-text-main);
        }

        /* Table Styles */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom thead th {
            background: var(--jm-table-header-bg);
            color: var(--jm-text-muted);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--jm-border);
        }

        .table-custom tbody td {
            padding: 0.95rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--jm-border-subtle);
            color: var(--jm-text-secondary);
            font-size: 0.88rem;
        }

        .table-custom tbody tr:hover td {
            background-color: var(--jm-table-hover);
        }

        /* Dark Mode Overrides */
        [data-theme="dark"] .bg-white {
            background-color: var(--jm-card-bg) !important;
        }
        [data-theme="dark"] .bg-light {
            background-color: #172239 !important;
            color: var(--jm-text-secondary) !important;
        }
        [data-theme="dark"] .border {
            border-color: var(--jm-border) !important;
        }
        [data-theme="dark"] .text-dark {
            color: #f8fafc !important;
        }
        [data-theme="dark"] .text-secondary {
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] .input-group-text {
            background-color: var(--jm-input-bg) !important;
            border-color: var(--jm-input-border) !important;
            color: var(--jm-text-main) !important;
        }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            border-color: var(--jm-accent) !important;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }
        [data-theme="dark"] .modal-content {
            background-color: var(--jm-card-bg);
            border: 1px solid var(--jm-border);
            color: var(--jm-text-main);
        }
        [data-theme="dark"] .modal-footer {
            background-color: #0e1628 !important;
            border-top-color: var(--jm-border) !important;
        }
        [data-theme="dark"] .dropdown-menu {
            background-color: #151e32;
            border: 1px solid var(--jm-border);
        }
        [data-theme="dark"] .dropdown-item {
            color: #cbd5e1;
        }
        [data-theme="dark"] .dropdown-item:hover {
            background-color: #1c273e;
            color: #ffffff;
        }
        [data-theme="dark"] .btn-light {
            background-color: #1e293b;
            border-color: var(--jm-border);
            color: #cbd5e1;
        }
        [data-theme="dark"] .btn-light:hover {
            background-color: #293952;
            color: #ffffff;
        }

        /* Footer */
        .footer-clean {
            margin-top: auto;
            background: var(--jm-footer-bg);
            border-top: 1px solid var(--jm-border);
            padding: 1.25rem 0;
            color: var(--jm-text-muted);
            font-size: 0.85rem;
        }

        /* Theme Toggle Button */
        .theme-toggle-pill {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.4rem 0.85rem;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle-pill:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.3);
        }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <!-- Sleek Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-medical sticky-top">
        <div class="container-fluid px-lg-5 px-3">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <span class="brand-logo-badge">
                    <i class="bi bi-hospital"></i>
                </span>
                <span>
                    Jajal<span class="text-primary-emphasis" style="color: #60a5fa !important;">Medical</span>
                    <span class="badge bg-white bg-opacity-10 text-white rounded-pill fw-normal ms-2" style="font-size: 0.65rem; letter-spacing: 0.03em;">CLINICAL</span>
                </span>
            </a>

            <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-pill-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-pill-link {{ request()->routeIs('cases.index') || request()->routeIs('cases.show') ? 'active' : '' }}" href="{{ route('cases.index') }}">
                            <i class="bi bi-folder2-open"></i> Implant Cases
                        </a>
                    </li>
                    @can('create', App\Models\MedicalCase::class)
                    <li class="nav-item">
                        <a class="nav-pill-link {{ request()->routeIs('cases.create') ? 'active' : '' }}" href="{{ route('cases.create') }}">
                            <i class="bi bi-plus-circle-dotted"></i> Log New Case
                        </a>
                    </li>
                    @endcan
                </ul>

                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <!-- Dark / Light Mode Toggle Button -->
                    <button type="button" class="theme-toggle-pill" id="themeToggleBtn" onclick="toggleTheme()" title="Switch Light / Dark Theme">
                        <i class="bi bi-moon-stars-fill text-warning" id="themeIcon"></i>
                        <span class="d-none d-md-inline" id="themeLabel">Dark Mode</span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="btn user-dropdown-btn d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user-avatar-circle">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <div class="text-start d-none d-sm-block">
                                <div class="fw-semibold text-white lh-sm" style="font-size: 0.85rem;">{{ Auth::user()->name }}</div>
                            </div>
                            <span class="role-pill role-pill-{{ Auth::user()->role }} ms-1">
                                {{ strtoupper(Auth::user()->role) }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 p-2" style="min-width: 240px;">
                            <li class="px-3 py-2 border-bottom mb-2">
                                <div class="small text-muted">Signed in as</div>
                                <div class="fw-bold font-mono small">{{ Auth::user()->email }}</div>
                                <div class="mt-1">
                                    <span class="role-pill role-pill-{{ Auth::user()->role }}">
                                        {{ ucfirst(Auth::user()->role) }} Role
                                    </span>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 small d-flex align-items-center gap-2" href="{{ route('cases.index') }}">
                                    <i class="bi bi-list-check text-primary"></i> Browse All Cases
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-2 py-2 small text-danger d-flex align-items-center gap-2 fw-semibold">
                                        <i class="bi bi-box-arrow-right"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @else
    <!-- Guest Header with Theme Toggle -->
    <header class="py-3 px-lg-5 px-3 d-flex justify-content-between align-items-center">
        <a class="navbar-brand text-dark d-flex align-items-center gap-2 text-decoration-none" href="{{ route('login') }}">
            <span class="brand-logo-badge" style="width: 32px; height: 32px; font-size: 1rem;">
                <i class="bi bi-hospital"></i>
            </span>
            <span class="fw-bold fs-5 text-dark">Jajal<span class="text-primary">Medical</span></span>
        </a>

        <!-- Guest Theme Switcher Button -->
        <button type="button" class="btn btn-sm btn-jm-secondary d-inline-flex align-items-center gap-2 rounded-pill px-3 py-1 shadow-sm" onclick="toggleTheme()" title="Switch Light / Dark Theme">
            <i class="bi bi-moon-stars-fill text-warning" id="themeIconGuest"></i>
            <span class="small fw-semibold" id="themeLabelGuest">Dark Mode</span>
        </button>
    </header>
    @endauth

    <!-- Main Content Container -->
    <main class="py-4 flex-grow-1">
        <div class="container-fluid px-lg-5 px-3">
            <!-- Flash Notification Messages -->
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center rounded-3 p-3 mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46;">
                    <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                    <div class="fw-medium">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show d-flex align-items-center rounded-3 p-3 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b;">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                    <div class="fw-medium">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info border-0 shadow-sm alert-dismissible fade show d-flex align-items-center rounded-3 p-3 mb-4" role="alert" style="background-color: #f0f9ff; color: #075985;">
                    <i class="bi bi-info-circle-fill me-2 fs-5 text-info"></i>
                    <div class="fw-medium">{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Professional Medical Footer -->
    <footer class="footer-clean">
        <div class="container-fluid px-lg-5 px-3">
            <div class="row align-items-center gy-2">
                <div class="col-md-6 text-md-start text-center">
                    <span class="fw-bold text-dark">Jajal Medical</span> &mdash; Patient-Specific Implants &amp; Surgical Lifecycle Platform &copy; {{ date('Y') }}
                </div>
                <div class="col-md-6 text-md-end text-center d-flex justify-content-md-end justify-content-center align-items-center gap-3">
                    <span class="badge bg-light text-secondary border px-2 py-1 small">
                        <i class="bi bi-shield-lock-fill text-success me-1"></i> HIPAA &amp; RBAC Enforced
                    </span>
                    <span class="badge bg-light text-secondary border px-2 py-1 small">
                        <i class="bi bi-cpu-fill text-primary me-1"></i> REST API Enabled
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dark / Light Theme Controller Script -->
    <script>
        function updateThemeUI(theme) {
            const isDark = (theme === 'dark');
            const icon = document.getElementById('themeIcon');
            const label = document.getElementById('themeLabel');
            const iconGuest = document.getElementById('themeIconGuest');
            const labelGuest = document.getElementById('themeLabelGuest');

            if (icon) {
                icon.className = isDark ? 'bi bi-sun-fill text-warning' : 'bi bi-moon-stars-fill text-warning';
            }
            if (label) {
                label.textContent = isDark ? 'Light Mode' : 'Dark Mode';
            }
            if (iconGuest) {
                iconGuest.className = isDark ? 'bi bi-sun-fill text-warning' : 'bi bi-moon-stars-fill text-warning';
            }
            if (labelGuest) {
                labelGuest.textContent = isDark ? 'Light Mode' : 'Dark Mode';
            }
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = (currentTheme === 'dark') ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('jm-theme', newTheme);
            updateThemeUI(newTheme);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            updateThemeUI(currentTheme);
        });
    </script>

    @yield('scripts')
</body>
</html>
