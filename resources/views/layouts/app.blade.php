<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Case Management') | Jajal Medical Implants</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
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
            --jm-border: #e2e8f0;
            --jm-border-subtle: #f1f5f9;
            --jm-text-main: #0f172a;
            --jm-text-muted: #64748b;
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
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Top Bar & Navbar */
        .navbar-medical {
            background: linear-gradient(180deg, #0f172a 0%, #0a0f1d 100%);
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

        /* Card Elevations & Surface */
        .card-clean {
            background: #ffffff;
            border: 1px solid var(--jm-border);
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .card-stat {
            background: #ffffff;
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
            box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
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
            background: #ffffff;
            border: 1px solid var(--jm-border);
            color: #334155;
            font-weight: 600;
            padding: 0.55rem 1.15rem;
            border-radius: 10px;
            transition: all 0.15s ease;
        }

        .btn-jm-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        /* Table Styles */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom thead th {
            background: #f8fafc;
            color: #475569;
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
            color: #334155;
            font-size: 0.88rem;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Footer */
        .footer-clean {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid var(--jm-border);
            padding: 1.25rem 0;
            color: var(--jm-text-muted);
            font-size: 0.85rem;
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

                <div class="d-flex align-items-center gap-3">
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
                                <div class="fw-bold text-dark font-mono small">{{ Auth::user()->email }}</div>
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
                    <span class="fw-bold text-dark">Jajal Medical</span> &mdash; Patient-Specific Implants & Surgical Lifecycle Platform &copy; {{ date('Y') }}
                </div>
                <div class="col-md-6 text-md-end text-center d-flex justify-content-md-end justify-content-center align-items-center gap-3">
                    <span class="badge bg-light text-secondary border px-2 py-1 small">
                        <i class="bi bi-shield-lock-fill text-success me-1"></i> HIPAA & RBAC Enforced
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
    @yield('scripts')
</body>
</html>
