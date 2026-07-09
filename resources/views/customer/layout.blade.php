<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Account') – PraiseStore</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--primary:#2563eb;--primary-dark:#1d4ed8;--dark:#0f172a;--border:#e2e8f0;--gray:#64748b;--light:#f0f7ff;--white:#fff}
        body{font-family:'Inter',sans-serif;background:var(--light);color:#1e293b;min-height:100vh}
        a{text-decoration:none;color:inherit}

        /* TOPBAR */
        .topbar{background:var(--dark);color:#9ca3af;font-size:12px;padding:7px 0}
        .topbar .inner{max-width:1280px;margin:0 auto;padding:0 16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:4px}
        .topbar a{color:#9ca3af;transition:color .2s}
        .topbar a:hover{color:var(--primary)}

        /* HEADER */
        .header{background:#fff;border-bottom:1px solid var(--border);padding:12px 0;position:sticky;top:0;z-index:100;box-shadow:0 1px 6px rgba(0,0,0,.05)}
        .header .inner{max-width:1280px;margin:0 auto;padding:0 16px;display:flex;align-items:center;justify-content:space-between;gap:12px}
        .logo{display:flex;align-items:center;gap:10px;font-size:20px;font-weight:800;color:var(--dark);flex-shrink:0}
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,#1d4ed8,#2563eb);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;flex-shrink:0}
        .logo-text span{color:var(--primary)}
        .header-nav{display:flex;align-items:center;gap:4px}
        .header-nav a{padding:7px 12px;border-radius:8px;font-size:13px;font-weight:500;color:var(--gray);transition:all .2s;white-space:nowrap}
        .header-nav a:hover{background:var(--light);color:var(--dark)}
        .header-nav a.active{background:rgba(37,99,235,.1);color:var(--primary);font-weight:600}
        .cart-btn{display:flex;align-items:center;gap:6px;padding:8px 14px;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;border-radius:8px;font-size:13px;font-weight:600;transition:background .2s;position:relative;white-space:nowrap;flex-shrink:0}
        .cart-btn:hover{background:var(--primary-dark)}
        .cart-count{background:#fff;color:var(--primary);width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800}
        .mob-menu-btn{display:none;background:none;border:none;cursor:pointer;font-size:22px;color:var(--dark);padding:4px;flex-shrink:0}

        /* PAGE WRAPPER */
        .page-wrap{max-width:1280px;margin:0 auto;padding:24px 16px 60px;display:grid;grid-template-columns:240px 1fr;gap:20px;align-items:start}

        /* SIDEBAR */
        .sidebar{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;position:sticky;top:72px}
        .sidebar-profile{padding:20px;background:linear-gradient(135deg,#0f172a,#1e3a8a,#1d4ed8);color:#fff;text-align:center}
        .avatar{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#1d4ed8,#3b82f6);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;margin:0 auto 10px;border:3px solid rgba(255,255,255,.2)}
        .sidebar-profile h3{font-size:14px;font-weight:700;margin-bottom:3px}
        .sidebar-profile p{font-size:11px;color:rgba(255,255,255,.6);word-break:break-all}
        .sidebar-nav{padding:8px 0}
        .nav-item{display:flex;align-items:center;gap:12px;padding:10px 18px;font-size:13px;font-weight:500;color:var(--gray);transition:all .2s;border-left:3px solid transparent;cursor:pointer}
        .nav-item:hover{background:var(--light);color:var(--dark)}
        .nav-item.active{background:rgba(37,99,235,.08);color:var(--primary);border-left-color:var(--primary);font-weight:600}
        .nav-item i{width:16px;text-align:center;font-size:13px;flex-shrink:0}
        .nav-divider{height:1px;background:var(--border);margin:4px 0}
        .nav-item.danger{color:#ef4444}
        .nav-item.danger:hover{background:#fef2f2}

        /* CONTENT */
        .content-area{display:flex;flex-direction:column;gap:16px;min-width:0}

        /* CARDS */
        .card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px}
        .card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:8px}
        .card-header h2{font-size:15px;font-weight:700;color:var(--dark);display:flex;align-items:center;gap:8px}
        .card-header h2 i{color:var(--primary)}

        /* FLASH */
        .flash{padding:11px 14px;border-radius:10px;font-size:13px;display:flex;align-items:center;gap:10px;margin-bottom:4px}
        .flash.success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
        .flash.error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}

        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .2s;white-space:nowrap}
        .btn-primary{background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff}
        .btn-primary:hover{background:var(--primary-dark)}
        .btn-secondary{background:var(--light);color:var(--dark);border:1px solid var(--border)}
        .btn-secondary:hover{background:var(--border)}
        .btn-sm{padding:5px 10px;font-size:12px;border-radius:6px}

        /* FORMS */
        .form-group{margin-bottom:14px}
        .form-group label{display:block;font-size:12px;font-weight:600;color:var(--dark);margin-bottom:5px}
        .form-control{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;outline:none;transition:border-color .2s;font-family:inherit;background:#fff}
        .form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.1)}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        .form-error{color:#ef4444;font-size:11px;margin-top:4px}

        /* BADGES */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700}
        .badge-pending{background:#fef3c7;color:#92400e}
        .badge-processing{background:#dbeafe;color:#1e40af}
        .badge-shipped{background:#e0e7ff;color:#3730a3}
        .badge-delivered{background:#d1fae5;color:#065f46}
        .badge-cancelled{background:#fee2e2;color:#991b1b}

        /* TABLE */
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
        table{width:100%;border-collapse:collapse;min-width:500px}
        th{background:#f8fafc;padding:9px 12px;text-align:left;font-size:11px;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
        td{padding:10px 12px;border-top:1px solid var(--border);font-size:12.5px;vertical-align:middle}
        tr:hover td{background:#fafbfc}

        /* Mobile sidebar overlay */
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:149}
        .sidebar-overlay.open{display:block}

        /* ── RESPONSIVE ── */
        /* Tablet: 1024px */
        @media(max-width:1024px){
            .page-wrap{grid-template-columns:220px 1fr;gap:16px}
        }
        /* Tablet portrait: 768px */
        @media(max-width:768px){
            .topbar{display:none}
            .mob-menu-btn{display:block}
            .header-nav{display:none}
            .page-wrap{grid-template-columns:1fr;padding:16px 12px 40px}
            .sidebar{
                position:fixed;top:0;left:0;height:100vh;z-index:150;
                width:260px;border-radius:0;overflow-y:auto;
                transform:translateX(-100%);transition:transform .3s ease;
            }
            .sidebar.open{transform:translateX(0)}
            .sidebar-profile{padding-top:56px}
            .content-area{gap:14px}
            .card{padding:16px}
            .form-row{grid-template-columns:1fr 1fr}
        }
        /* Mobile: 480px */
        @media(max-width:480px){
            .header .inner{padding:0 12px;gap:8px}
            .logo{font-size:17px}
            .logo-icon{width:32px;height:32px;font-size:13px}
            .cart-btn span.cart-label{display:none}
            .cart-btn{padding:8px 10px}
            .card{padding:14px}
            .card-header{flex-direction:column;align-items:flex-start}
            .form-row{grid-template-columns:1fr}
            table{min-width:420px}
            th,td{padding:8px 10px;font-size:11.5px}
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div class="inner">
        <span><i class="fas fa-map-marker-alt"></i> Kigali, Rwanda &nbsp;|&nbsp; <i class="fas fa-phone"></i> +250 795 9151</span>
        <span>Welcome back, <strong style="color:var(--primary)">{{ auth()->user()->name }}</strong>! &nbsp;|&nbsp;
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:12px">Logout</button>
            </form>
        </span>
    </div>
</div>

{{-- HEADER --}}
<header class="header">
    <div class="inner">
        <button class="mob-menu-btn" onclick="openCustSidebar()" aria-label="Menu"><i class="fas fa-bars"></i></button>
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-icon"><i class="fas fa-store"></i></div>
            <div class="logo-text">Praise<span>Store</span></div>
        </a>
        <nav class="header-nav">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
            <a href="{{ route('shop') }}"><i class="fas fa-tshirt"></i> Shop</a>
            <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.*') ? 'active' : '' }}"><i class="fas fa-user"></i> My Account</a>
        </nav>
        <a href="{{ route('cart') }}" class="cart-btn">
            <i class="fas fa-shopping-bag"></i>
            @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
            @if($cartCount > 0)<span class="cart-count">{{ $cartCount }}</span>@endif
            <span class="cart-label">Cart</span>
        </a>
    </div>
</header>

{{-- SIDEBAR OVERLAY --}}
<div class="sidebar-overlay" id="custSidebarOverlay" onclick="closeCustSidebar()"></div>

{{-- PAGE --}}
<div class="page-wrap">
    {{-- SIDEBAR --}}
    <aside class="sidebar" id="custSidebar">
        {{-- Mobile close button --}}
        <button onclick="closeCustSidebar()" style="display:none;position:absolute;top:12px;right:12px;background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:14px;z-index:1;align-items:center;justify-content:center" id="sidebarCloseBtn"><i class="fas fa-times"></i></button>
        <div class="sidebar-profile">
            @if(auth()->user()->profile_photo_path)
                <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="Avatar" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.2);margin:0 auto 10px;display:block">
            @else
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            @endif
            <h3>{{ auth()->user()->name }}</h3>
            <p>{{ auth()->user()->email }}</p>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('customer.dashboard') }}" class="nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('customer.profile') }}" class="nav-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i> My Profile
            </a>
            <a href="{{ route('customer.orders') }}" class="nav-item {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> My Orders
            </a>
            <a href="{{ route('customer.orders') }}#track" class="nav-item">
                <i class="fas fa-map-marker-alt"></i> Track Order
            </a>
            <div class="nav-divider"></div>
            <a href="{{ route('shop') }}" class="nav-item"><i class="fas fa-tshirt"></i> Continue Shopping</a>
            <a href="{{ route('cart') }}" class="nav-item"><i class="fas fa-shopping-cart"></i> View Cart</a>
            <div class="nav-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item danger" style="width:100%;background:none;border:none;text-align:left;font-family:inherit;cursor:pointer">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="content-area">
        @if(session('success'))
            <div class="flash success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

@stack('scripts')
<script>
function openCustSidebar(){
    document.getElementById('custSidebar').classList.add('open');
    document.getElementById('custSidebarOverlay').classList.add('open');
    document.getElementById('sidebarCloseBtn').style.display='flex';
    document.body.style.overflow='hidden';
}
function closeCustSidebar(){
    document.getElementById('custSidebar').classList.remove('open');
    document.getElementById('custSidebarOverlay').classList.remove('open');
    document.getElementById('sidebarCloseBtn').style.display='none';
    document.body.style.overflow='';
}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeCustSidebar();});
</script>
</body>
</html>
