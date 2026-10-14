<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Medical Case Management') - Jajal Medical</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --jm-navy: #0f2b48;
            --jm-navy-dark: #0a1c30;
            --jm-blue: #1e6091;
            --jm-light-bg: #f4f7fa;
            --jm-card-bg: #ffffff;
            --jm-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--jm-light-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-medical {
            background-color: var(--jm-navy);
            box-shadow: 0 2px 10px rgba(15, 43, 72, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand .brand-icon {
            background: #2563eb;
            color: white;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link {
            color: #cbd5e1 !important;
            font-weight: 500;
            padding: 0.5rem 0.85rem !important;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .role-badge {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 0.3em 0.7em;
            border-radius: 20px;
        }

        .badge-role-admin {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-role-viewer {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-role-engineer {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-status-draft { background-color: #e2e8f0; color: #475569; }
        .badge-status-planning { background-color: #dbeafe; color: #1e40af; }
        .badge-status-in-review { background-color: #fef3c7; color: #92400e; }
        .badge-status-approved { background-color: #ccfbf1; color: #115e59; }
        .badge-status-completed { background-color: #dcfce7; color: #166534; }
        .badge-status-cancelled { background-color: #fee2e2; color: #991b1b; }

        .badge-priority-low { background-color: #f1f5f9; color: #64748b; }
        .badge-priority-medium { background-color: #fef3c7; color: #b45309; }
        .badge-priority-high { background-color: #fee2e2; color: #dc2626; }

        .card {
            border: 1px solid var(--jm-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            background-color: var(--jm-card-bg);
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--jm-border);
            font-weight: 600;
        }

        .btn-medical {
            background-color: var(--jm-navy);
            color: #ffffff;
            border: none;
            font-weight: 500;
        }
        .btn-medical:hover {
            background-color: var(--jm-blue);
            color: #ffffff;
        }

        .footer {
            margin-top: auto;
            background-color: #ffffff;
            border-top: 1px solid var(--jm-border);
            padding: 1.25rem 0;
            font-size: 0.875rem;
            color: #64748b;
        }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-medical sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <span class="brand-icon"><i class="bi bi-hospital"></i></span>
                <span>Jajal Medical</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#medicalNavbar" aria-controls="medicalNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="medicalNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cases.index') || request()->routeIs('cases.show') ? 'active' : '' }}" href="{{ route('cases.index') }}">
                            <i class="bi bi-folder2-open me-1"></i> Medical Cases
                        </a>
                    </li>
                    @can('create', App\Models\MedicalCase::class)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cases.create') ? 'active' : '' }}" href="{{ route('cases.create') }}">
                            <i class="bi bi-plus-circle me-1"></i> New Case
                        </a>
                    </li>
                    @endcan
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <span class="role-badge badge-role-{{ Auth::user()->role }}">
                                {{ strtoupper(Auth::user()->role) }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                            <li class="px-3 py-2 border-bottom">
                                <div class="small text-muted">Signed in as</div>
                                <div class="fw-bold">{{ Auth::user()->email }}</div>
                                <div class="small text-primary">Role: {{ ucfirst(Auth::user()->role) }}</div>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="p-1">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 rounded">
                                        <i class="bi bi-box-arrow-right"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content Container -->
    <main class="py-4 flex-grow-1">
        <div class="container-fluid px-4">
            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any() && !request()->routeIs('login'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon me-1"></i> Please resolve the following errors:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid px-4 text-center">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start mb-2 mb-md-0">
                    <strong>Jajal Medical</strong> &copy; {{ date('Y') }} &mdash; Patient-Specific Implant Case Management
                </div>
                <div class="col-md-6 text-md-end text-muted">
                    <span class="badge bg-light text-dark border me-2"><i class="bi bi-shield-check text-success me-1"></i> HIPAA-Compliant Architecture</span>
                    <span class="small">Internal Clinical Portal</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
