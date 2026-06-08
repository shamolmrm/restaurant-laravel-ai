<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary: #8B0000; --primary-dark: #6B0000; --primary-light: #A50000;
            --secondary: #0A2647; --accent: #D4AF37;
            --bg: #F5F7FA; --sidebar-width: 260px; --topbar-height: 60px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: #2d3748; margin: 0; }
        #sidebar {
            position: fixed; left: 0; top: 0; width: var(--sidebar-width);
            height: 100vh; background: var(--secondary); z-index: 1000;
            display: flex; flex-direction: column; overflow-y: auto; overflow-x: hidden; transition: all 0.3s;
        }
        .sidebar-brand { padding: 18px 20px 14px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand .brand-name { color: var(--accent); font-weight: 700; font-size: 1rem; }
        .sidebar-brand .brand-sub { color: rgba(255,255,255,0.45); font-size: 0.72rem; }
        .nav-section-title { padding: 14px 20px 4px; font-size: 0.63rem; font-weight: 600; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 1.2px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px; padding: 9px 20px;
            color: rgba(255,255,255,0.72); text-decoration: none; font-size: 0.845rem; font-weight: 500;
            border-left: 3px solid transparent; transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.08); color: #fff; border-left-color: var(--accent);
        }
        .sidebar-link i { font-size: 1.05rem; width: 20px; text-align: center; }
        .sidebar-footer { margin-top: auto; padding: 14px 16px; border-top: 1px solid rgba(255,255,255,0.1); }
        #main { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        #topbar {
            height: var(--topbar-height); background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07); display: flex;
            align-items: center; padding: 0 20px; gap: 12px;
            position: sticky; top: 0; z-index: 990;
        }
        .topbar-title { flex: 1; font-size: 1rem; font-weight: 600; color: var(--secondary); }
        .icon-btn { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: var(--bg); border: none; color: #555; cursor: pointer; text-decoration: none; transition: all 0.2s; position: relative; }
        .icon-btn:hover { background: #e2e8f0; color: var(--primary); }
        .notif-dot { position: absolute; top: 2px; right: 2px; width: 14px; height: 14px; background: var(--primary); color: #fff; border-radius: 50%; font-size: 0.58rem; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .page-content { padding: 24px; flex: 1; }
        .page-header { margin-bottom: 22px; }
        .page-header h4 { font-weight: 700; color: var(--secondary); margin: 0; }
        .page-header .text-muted { font-size: 0.85rem; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 8px rgba(0,0,0,0.06); }
        .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 14px 20px; font-weight: 600; font-size: 0.9rem; }
        .stat-card { border-radius: 12px; padding: 22px; color: #fff; overflow: hidden; }
        .stat-icon { width: 46px; height: 46px; border-radius: 10px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; }
        .stat-value { font-size: 1.7rem; font-weight: 700; margin: 10px 0 2px; }
        .stat-label { font-size: 0.8rem; opacity: 0.85; }
        .bg-grad-primary { background: linear-gradient(135deg, #8B0000, #C62828); }
        .bg-grad-secondary { background: linear-gradient(135deg, #0A2647, #155E9F); }
        .bg-grad-success { background: linear-gradient(135deg, #1a7f5a, #22a06b); }
        .bg-grad-warning { background: linear-gradient(135deg, #c0760c, #f39c12); }
        .bg-grad-info { background: linear-gradient(135deg, #0e7490, #0891b2); }
        .bg-grad-purple { background: linear-gradient(135deg, #5b21b6, #7c3aed); }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); color: #fff; }
        .table thead th { background: #f8fafc; font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 11px 14px; }
        .table tbody td { padding: 11px 14px; vertical-align: middle; border-color: #f1f5f9; font-size: 0.87rem; }
        .form-control, .form-select { border-radius: 8px; border-color: #e2e8f0; font-size: 0.875rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139,0,0,0.1); }
        .form-label { font-weight: 500; font-size: 0.84rem; }
        .badge { font-weight: 500; padding: 4px 8px; border-radius: 5px; }
        .alert { border: none; border-radius: 10px; font-size: 0.875rem; }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main { margin-left: 0 !important; }
        }
        ::-webkit-scrollbar { width: 4px; } ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
        .page-loader { position: fixed; inset: 0; background: var(--secondary); display: flex; align-items: center; justify-content: center; z-index: 9999; flex-direction: column; gap: 16px; }
        .loader-ring { width: 44px; height: 44px; border: 3px solid rgba(255,255,255,0.15); border-top-color: var(--accent); border-radius: 50%; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        /* Pagination */
        .pagination { margin: 0; gap: 3px; flex-wrap: wrap; }
        .pagination .page-item .page-link { border-radius: 6px !important; font-size: 0.8rem; padding: 4px 10px; color: var(--secondary); border-color: #e2e8f0; line-height: 1.5; }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }
        .pagination .page-item.disabled .page-link { color: #adb5bd; }
        .pagination .page-item .page-link:hover { background: #f1f5f9; color: var(--primary); }
        .pagination .page-item.active .page-link:hover { background: var(--primary-dark); }
        .card-footer { background: transparent; border-top: 1px solid #f1f5f9; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; font-size: 0.8rem; color: #64748b; }
    </style>
    @stack('styles')
</head>
<body>

<div class="page-loader" id="pageLoader">
    <div class="loader-ring"></div>
    <span style="color:rgba(255,255,255,0.7);font-size:0.85rem">Loading...</span>
</div>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:32px;height:32px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-cup-hot-fill text-white" style="font-size:1rem"></i>
            </div>
            <div>
                <div class="brand-name">Grand RMS</div>
                <div class="brand-sub">Restaurant Management</div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1">
        <div class="nav-section-title">Main</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
        @can('access pos')
        <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <i class="bi bi-cart3"></i><span>POS System</span>
        </a>
        @endcan
        @can('view kitchen')
        <a href="{{ route('kitchen.index') }}" class="sidebar-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}">
            <i class="bi bi-fire"></i><span>Kitchen Display</span>
        </a>
        @endcan

        <div class="nav-section-title">Orders & Tables</div>
        @can('view orders')
        <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i><span>Orders</span>
        </a>
        @endcan
        @can('view tables')
        <a href="{{ route('tables.index') }}" class="sidebar-link {{ request()->routeIs('tables.*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap"></i><span>Tables</span>
        </a>
        @endcan
        @can('view reservations')
        <a href="{{ route('reservations.index') }}" class="sidebar-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i><span>Reservations</span>
        </a>
        @endcan
        @can('view delivery')
        <a href="{{ route('delivery.index') }}" class="sidebar-link {{ request()->routeIs('delivery.*') ? 'active' : '' }}">
            <i class="bi bi-bicycle"></i><span>Delivery</span>
        </a>
        @endcan

        <div class="nav-section-title">Menu</div>
        @can('view categories')
        <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i><span>Categories</span>
        </a>
        @endcan
        @can('view menu')
        <a href="{{ route('menu.index') }}" class="sidebar-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
            <i class="bi bi-menu-button-wide"></i><span>Menu Items</span>
        </a>
        @endcan

        <div class="nav-section-title">People</div>
        @can('view customers')
        <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i><span>Customers</span>
        </a>
        @endcan
        @can('view employees')
        <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i><span>Employees</span>
        </a>
        @endcan
        @can('view attendance')
        <a href="{{ route('employees.attendance') }}" class="sidebar-link {{ request()->routeIs('employees.attendance') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i><span>Attendance</span>
        </a>
        @endcan

        <div class="nav-section-title">Inventory</div>
        @can('view inventory')
        <a href="{{ route('inventory.index') }}" class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i><span>Inventory</span>
        </a>
        @endcan
        @can('view suppliers')
        <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i><span>Suppliers</span>
        </a>
        @endcan
        @can('view purchases')
        <a href="{{ route('purchases.index') }}" class="sidebar-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i><span>Purchases</span>
        </a>
        @endcan

        <div class="nav-section-title">Finance & Reports</div>
        @can('view coupons')
        <a href="{{ route('coupons.index') }}" class="sidebar-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
            <i class="bi bi-percent"></i><span>Coupons</span>
        </a>
        @endcan
        @can('view reports')
        <a href="{{ route('reports.sales') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i><span>Reports</span>
        </a>
        @endcan

        <div class="nav-section-title">System</div>
        @can('view users')
        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i><span>Users</span>
        </a>
        @endcan
        @can('view settings')
        <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i><span>Settings</span>
        </a>
        @endcan
    </div>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:32px;height:32px;background:var(--primary);font-size:0.8rem">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>
            <div style="overflow:hidden">
                <div class="text-white" style="font-size:0.8rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name }}</div>
                <div style="font-size:0.68rem;color:rgba(255,255,255,0.45)">{{ ucfirst(str_replace('_',' ',auth()->user()->getRoleNames()->first() ?? 'user')) }}</div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div id="main">
    <div id="topbar">
        <button class="icon-btn" onclick="toggleSidebar()" style="flex-shrink:0">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="topbar-title d-none d-sm-block">@yield('title', 'Dashboard')</span>
        <div style="margin-left:auto;display:flex;align-items:center;gap:8px">
            <a href="{{ route('notifications.index') }}" class="icon-btn" id="notifBtn">
                <i class="bi bi-bell fs-5"></i>
                <span class="notif-dot d-none" id="notifCount">0</span>
            </a>
            <div class="dropdown">
                <button style="background:none;border:none;padding:0;cursor:pointer" data-bs-toggle="dropdown">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:36px;height:36px;background:var(--primary);font-size:0.85rem">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow" style="border-radius:10px;min-width:160px">
                    <li><h6 class="dropdown-header small">{{ auth()->user()->name }}</h6></li>
                    <li><a class="dropdown-item small" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item small text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <ul class="mb-0 ps-3 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener('load', () => document.getElementById('pageLoader').style.display = 'none');
    function toggleSidebar() {
        const s = document.getElementById('sidebar');
        if (window.innerWidth <= 768) s.classList.toggle('open');
        else s.classList.toggle('collapsed');
    }
    function loadNotif() {
        fetch('{{ route("notifications.unread-count") }}')
            .then(r => r.json()).then(d => {
                const el = document.getElementById('notifCount');
                if (d.count > 0) { el.textContent = d.count > 9 ? '9+' : d.count; el.classList.remove('d-none'); }
                else el.classList.add('d-none');
            }).catch(()=>{});
    }
    loadNotif(); setInterval(loadNotif, 60000);
    setTimeout(() => { document.querySelectorAll('.alert').forEach(a => { try { bootstrap.Alert.getOrCreateInstance(a).close(); } catch(e){} }); }, 5000);
</script>
@stack('scripts')
</body>
</html>
