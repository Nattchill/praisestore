<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PraiseStore – Fashion for Everyone')</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{
            --primary:#e1306c;
            --primary-dark:#c13584;
            --ig-gradient:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);
            --ig-soft:linear-gradient(135deg,#ffecd2,#fcb69f,#ffeaa7,#fd79a8,#e84393);
            --dark:#2d1b3d;
            --gray:#8e6b8e;
            --light:#fdf0f8;
            --white:#fff;
            --border:#f0d6e8;
            --bg:#fafafa;
            --surface:#ffffff;
            --nav-bg:#1a0a2e;
        }
        body{font-family:'Inter',sans-serif;color:#2d1b3d;background:#fafafa}
        a{text-decoration:none;color:inherit}
        img{max-width:100%}
        .container{max-width:1280px;margin:0 auto;padding:0 20px}

        /* ── TOP BAR ── */
        .topbar{background:var(--ig-gradient);color:#fff;font-size:12px;padding:7px 0;line-height:1.4}
        .topbar .container{display:flex;justify-content:space-between;align-items:center;gap:8px}
        .topbar a{color:#fff;transition:opacity .2s}
        .topbar a:hover{opacity:.8}
        .topbar-left,.topbar-right{display:flex;align-items:center;gap:6px;flex-wrap:wrap}

        /* ── HEADER ── */
        header{background:#fff;border-bottom:1px solid var(--border);padding:12px 0;position:sticky;top:0;z-index:200;box-shadow:0 2px 16px rgba(193,53,132,.08)}
        .header-inner{display:flex;align-items:center;gap:16px}
        .logo{display:flex;align-items:center;gap:10px;font-size:22px;font-weight:700;color:var(--dark);white-space:nowrap;flex-shrink:0}
        .logo-icon{width:38px;height:38px;background:var(--ig-gradient);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:17px;flex-shrink:0}
        .search-bar{flex:1;display:flex;min-width:0;max-width:520px}
        .search-bar select{border:1px solid var(--border);border-right:none;padding:9px 10px;border-radius:6px 0 0 6px;font-size:12px;background:#f9fafb;cursor:pointer;max-width:110px}
        .search-bar input{flex:1;border:1px solid var(--border);border-right:none;padding:9px 12px;font-size:13px;outline:none;min-width:0}
        .search-bar button{background:var(--ig-gradient);color:#fff;border:none;padding:9px 16px;border-radius:0 6px 6px 0;cursor:pointer;font-size:14px;flex-shrink:0}
        .search-bar button:hover{opacity:.9}
        .header-actions{display:flex;align-items:center;gap:14px;margin-left:auto;flex-shrink:0}
        .header-actions a{display:flex;flex-direction:column;align-items:center;font-size:11px;color:var(--gray);gap:2px;position:relative;white-space:nowrap}
        .header-actions a:hover{color:var(--primary)}
        .header-actions a i{font-size:20px}
        .badge{position:absolute;top:-6px;right:-8px;background:var(--primary);color:#fff;font-size:10px;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700}
        .hotline{display:flex;align-items:center;gap:8px;font-size:13px;white-space:nowrap;flex-shrink:0}
        .hotline i{color:var(--primary);font-size:18px}
        .hotline span{font-size:11px;color:var(--gray)}
        .hotline strong{display:block;font-size:13px;color:var(--dark)}
        /* Hamburger */
        .menu-toggle{display:none;background:none;border:none;cursor:pointer;padding:6px;color:var(--dark);font-size:22px;flex-shrink:0}

        /* ── NAV ── */
        nav{background:var(--nav-bg);border-bottom:2px solid rgba(225,48,108,.3)}
        .nav-inner{display:flex;align-items:center}
        .browse-btn{background:var(--ig-gradient);color:#fff;padding:13px 20px;font-weight:600;font-size:13px;display:flex;align-items:center;gap:8px;cursor:pointer;white-space:nowrap;min-width:200px;transition:opacity .2s;flex-shrink:0;border:none}
        .browse-btn:hover{opacity:.9}
        .nav-links{display:flex;align-items:center;gap:2px;padding:0 12px;flex:1;overflow-x:auto;scrollbar-width:none}
        .nav-links::-webkit-scrollbar{display:none}
        .nav-links a{padding:13px 12px;font-size:13px;font-weight:500;color:rgba(255,255,255,.7);display:flex;align-items:center;gap:4px;transition:all .2s;white-space:nowrap}
        .nav-links a:hover,.nav-links a.active{color:#f09433}
        /* Mobile drawer */
        .mobile-nav{display:none;position:fixed;top:0;left:0;width:280px;height:100vh;background:var(--nav-bg);z-index:300;flex-direction:column;overflow-y:auto;transform:translateX(-100%);transition:transform .3s ease;box-shadow:4px 0 20px rgba(0,0,0,.3)}
        .mobile-nav.open{transform:translateX(0)}
        .mobile-nav-header{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid rgba(255,255,255,.1)}
        .mobile-nav-header .logo{color:#fff;font-size:18px}
        .mobile-nav-close{background:none;border:none;color:rgba(255,255,255,.6);font-size:20px;cursor:pointer}
        .mobile-nav-links{padding:12px 0}
        .mobile-nav-links a{display:flex;align-items:center;gap:12px;padding:13px 20px;font-size:14px;font-weight:500;color:rgba(255,255,255,.75);border-left:3px solid transparent;transition:all .2s}
        .mobile-nav-links a:hover,.mobile-nav-links a.active{color:#f09433;border-left-color:#f09433;background:rgba(255,255,255,.05)}
        .mobile-nav-links a i{width:18px;text-align:center;font-size:14px}
        .mobile-nav-divider{height:1px;background:rgba(255,255,255,.08);margin:8px 20px}
        .mobile-nav-user{padding:16px 20px;border-top:1px solid rgba(255,255,255,.1);margin-top:auto}
        .mobile-nav-user p{font-size:12px;color:rgba(255,255,255,.4);margin-bottom:10px}
        .mobile-nav-user a,.mobile-nav-user button{display:block;width:100%;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:600;text-align:center;margin-bottom:8px;cursor:pointer;border:none;font-family:inherit}
        .mobile-nav-user .btn-login{background:var(--ig-gradient);color:#fff}
        .mobile-nav-user .btn-register{background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2)}
        .mobile-nav-user .btn-logout{background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.2)}
        .nav-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:299}
        .nav-overlay.open{display:block}

        /* ── FLASH ── */
        .flash{padding:11px 20px;margin:0;font-size:13px;text-align:center}
        .flash.success{background:#d1fae5;color:#065f46}
        .flash.error{background:#fee2e2;color:#991b1b}

        /* ── FOOTER ── */
        footer{background:var(--nav-bg);color:#c9a8d4;margin-top:60px}
        .footer-top{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;padding:50px 0 30px}
        .footer-brand .logo{color:#fff;margin-bottom:14px}
        .footer-brand p{font-size:13px;line-height:1.7;margin-bottom:16px}
        .footer-social{display:flex;gap:10px;flex-wrap:wrap}
        .footer-social a{width:34px;height:34px;background:rgba(255,255,255,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;transition:background .2s}
        .footer-social a:hover{background:var(--ig-gradient)}
        .footer-col h4{color:#f09433;font-size:14px;margin-bottom:14px;font-weight:700}
        .footer-col ul{list-style:none}
        .footer-col ul li{margin-bottom:8px}
        .footer-col ul li a{font-size:13px;color:#c9a8d4;transition:color .2s}
        .footer-col ul li a:hover{color:var(--primary)}
        .footer-bottom{border-top:1px solid rgba(225,48,108,.2);padding:16px 0;text-align:center;font-size:12px;color:#c9a8d4}

        /* ══ RESPONSIVE BREAKPOINTS ══ */

        /* Tablet landscape: 1024px */
        @media(max-width:1024px){
            .footer-top{grid-template-columns:1fr 1fr;gap:28px}
            .browse-btn{min-width:160px;padding:13px 14px;font-size:12px}
        }

        /* Tablet portrait: 768px */
        @media(max-width:768px){
            .topbar .container{justify-content:center;text-align:center}
            .topbar-left{display:none}
            .topbar-right{justify-content:center;width:100%}
            .menu-toggle{display:flex}
            .hotline{display:none}
            nav .container .nav-inner{display:none}
            .search-bar select{display:none}
            .search-bar{max-width:100%}
            .header-inner{flex-wrap:nowrap;gap:10px}
            .logo span.logo-text-label{display:none}
            .footer-top{grid-template-columns:1fr 1fr;gap:24px;padding:36px 0 20px}
        }

        /* Mobile: 480px */
        @media(max-width:480px){
            .topbar{display:none}
            header{padding:10px 0}
            .logo{font-size:18px}
            .logo-icon{width:34px;height:34px;font-size:15px}
            .search-bar input{font-size:12px;padding:8px 10px}
            .search-bar button{padding:8px 12px;font-size:13px}
            .header-actions{gap:10px}
            .header-actions a span{display:none}
            .header-actions a i{font-size:22px}
            .footer-top{grid-template-columns:1fr;gap:20px;padding:28px 0 16px}
            .footer-bottom{font-size:11px;padding:14px 0}
            .help-popup{width:calc(100vw - 32px);right:16px;bottom:80px}
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- TOP BAR --}}
<div class="topbar">
    <div class="container">
        <span class="topbar-left"><i class="fas fa-map-marker-alt"></i> Kigali, Rwanda &nbsp;|&nbsp; <i class="fas fa-envelope"></i> davidfnatt2002@gmail.com</span>
        <span class="topbar-right">
            @auth
                Welcome, {{ auth()->user()->name }}!
                @if(auth()->user()->is_admin)
                    &nbsp;<a href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> Admin</a>
                @endif
                &nbsp;|&nbsp;
                <a href="{{ route('customer.dashboard') }}"><i class="fas fa-user"></i> My Account</a>
                &nbsp;|&nbsp;
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:#fff;cursor:pointer;font-size:12px">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a> &nbsp;|&nbsp; <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Register</a>
            @endauth
        </span>
    </div>
</div>

{{-- HEADER --}}
<header>
    <div class="container">
        <div class="header-inner">
            <button class="menu-toggle" onclick="openMobileNav()" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon"><i class="fas fa-store"></i></div>
                <span class="logo-text-label">PraiseStore</span>
            </a>
            <form class="search-bar" action="{{ route('shop') }}" method="GET">
                <select name="category">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::all() as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="hotline">
                <i class="fas fa-phone-alt"></i>
                <div>
                    <span>Hotline:</span>
                    <strong>+250 795 9151</strong>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('cart') }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Cart</span>
                    @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                    @if($cartCount > 0)<span class="badge">{{ $cartCount }}</span>@endif
                </a>
                @auth
                    <a href="{{ route('customer.dashboard') }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Account</span>
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <i class="fas fa-user"></i>
                        <span>Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- DESKTOP NAV --}}
<nav>
    <div class="container">
        <div class="nav-inner">
            <button class="browse-btn">
                <i class="fas fa-bars"></i> Browse Categories <i class="fas fa-chevron-down" style="margin-left:auto"></i>
            </button>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a>
                <a href="{{ route('shop') }}?category=women">Women</a>
                <a href="{{ route('shop') }}?category=men">Men</a>
                <a href="{{ route('shop') }}?category=kids">Kids</a>
                <a href="{{ route('shop') }}?category=accessories">Accessories</a>
                <a href="{{ route('cart') }}">Cart</a>
                @auth
                <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.*') ? 'active' : '' }}">My Account</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- MOBILE NAV OVERLAY --}}
<div class="nav-overlay" id="navOverlay" onclick="closeMobileNav()"></div>

{{-- MOBILE DRAWER --}}
<div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-header">
        <a href="{{ route('home') }}" class="logo" onclick="closeMobileNav()">
            <div class="logo-icon"><i class="fas fa-store"></i></div>
            PraiseStore
        </a>
        <button class="mobile-nav-close" onclick="closeMobileNav()"><i class="fas fa-times"></i></button>
    </div>
    <div class="mobile-nav-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> Home</a>
        <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}"><i class="fas fa-store"></i> Shop</a>
        <a href="{{ route('shop') }}?category=women"><i class="fas fa-female"></i> Women</a>
        <a href="{{ route('shop') }}?category=men"><i class="fas fa-male"></i> Men</a>
        <a href="{{ route('shop') }}?category=kids"><i class="fas fa-child"></i> Kids</a>
        <a href="{{ route('shop') }}?category=accessories"><i class="fas fa-gem"></i> Accessories</a>
        <div class="mobile-nav-divider"></div>
        <a href="{{ route('cart') }}"><i class="fas fa-shopping-bag"></i> Cart
            @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
            @if($cartCount > 0)<span style="background:var(--primary);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;margin-left:4px">{{ $cartCount }}</span>@endif
        </a>
        @auth
        <a href="{{ route('customer.dashboard') }}"><i class="fas fa-user-circle"></i> My Account</a>
        <a href="{{ route('customer.orders') }}"><i class="fas fa-box"></i> My Orders</a>
        @if(auth()->user()->is_admin)
        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> Admin Panel</a>
        @endif
        @endauth
    </div>
    <div class="mobile-nav-user">
        @auth
        <p>Logged in as <strong style="color:#fff">{{ auth()->user()->name }}</strong></p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
        @else
        <a href="{{ route('login') }}" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login</a>
        <a href="{{ route('register') }}" class="btn-register"><i class="fas fa-user-plus"></i> Register</a>
        @endauth
    </div>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div class="flash success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

{{-- CONTENT --}}
@yield('content')

{{-- FOOTER --}}
<footer>
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="logo"><div class="logo-icon"><i class="fas fa-store"></i></div> PraiseStore</div>
                <p>Your premier fashion destination in Rwanda. Quality clothing and accessories for the whole family.</p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop') }}">Shop</a></li>
                    <li><a href="{{ route('cart') }}">Cart</a></li>
                    <li><a href="{{ route('checkout') }}">Checkout</a></li>
                    @auth
                    <li><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                    <li><a href="{{ route('customer.orders') }}">My Orders</a></li>
                    @endauth
                </ul>
            </div>
            <div class="footer-col">
                <h4>Categories</h4>
                <ul>
                    <li><a href="{{ route('shop') }}?category=women">Women</a></li>
                    <li><a href="{{ route('shop') }}?category=men">Men</a></li>
                    <li><a href="{{ route('shop') }}?category=kids">Kids</a></li>
                    <li><a href="{{ route('shop') }}?category=accessories">Accessories</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="#">Kigali, Rwanda</a></li>
                    <li><a href="tel:+250791591773">+250 795 9151</a></li>
                    <li><a href="mailto:davidfnatt2002@gmail.com">davidfnatt2002@gmail.com</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} PraiseStore. All rights reserved. Built with ❤️ in Rwanda.
        </div>
    </div>
</footer>

@stack('scripts')

{{-- HELP & LIVE CHAT WIDGET --}}
<style>
/* ── FAB BUTTON ── */
.help-fab{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;gap:12px}
.help-fab-btn{
    width:58px;height:58px;border-radius:50%;
    background:var(--ig-gradient);color:#fff;border:none;
    font-size:22px;cursor:pointer;
    box-shadow:0 6px 24px rgba(225,48,108,.5);
    display:flex;align-items:center;justify-content:center;
    transition:transform .25s,box-shadow .25s;position:relative;
}
.help-fab-btn:hover{transform:scale(1.1);box-shadow:0 8px 32px rgba(225,48,108,.6)}
.help-fab-badge{
    position:absolute;top:-3px;right:-3px;
    background:#ef4444;color:#fff;font-size:10px;font-weight:700;
    min-width:18px;height:18px;border-radius:9px;padding:0 4px;
    display:none;align-items:center;justify-content:center;
    border:2px solid #fff;line-height:1;
}

/* ── POPUP SHELL ── */
.help-popup{
    position:fixed;bottom:96px;right:24px;
    width:360px;
    background:#fff;
    border-radius:20px;
    box-shadow:0 12px 48px rgba(0,0,0,.22),0 2px 8px rgba(0,0,0,.08);
    z-index:9998;
    display:none;
    flex-direction:column;
    overflow:hidden;
    animation:popIn .22s cubic-bezier(.34,1.56,.64,1);
    border:1px solid rgba(225,48,108,.12);
    max-height:calc(100vh - 120px);
}
.help-popup.open{display:flex}
@keyframes popIn{from{opacity:0;transform:translateY(16px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}

/* ── HEADER ── */
.help-header{
    background:var(--ig-gradient);
    padding:16px 18px 14px;
    color:#fff;
    flex-shrink:0;
    display:flex;align-items:flex-start;justify-content:space-between;
}
.help-header-text h3{font-size:15px;font-weight:700;margin-bottom:3px;display:flex;align-items:center;gap:8px}
.help-header-text p{font-size:12px;opacity:.85;line-height:1.4}
.help-close-btn{
    background:rgba(255,255,255,.2);border:none;color:#fff;
    width:28px;height:28px;border-radius:50%;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    font-size:13px;flex-shrink:0;transition:background .2s;
}
.help-close-btn:hover{background:rgba(255,255,255,.35)}

/* ── TABS ── */
.help-tabs{
    display:flex;background:#fff;
    border-bottom:2px solid #f5e6f0;
    flex-shrink:0;
}
.help-tab{
    flex:1;padding:11px 8px;font-size:12.5px;font-weight:600;
    text-align:center;cursor:pointer;color:#9ca3af;
    border-bottom:2px solid transparent;margin-bottom:-2px;
    transition:all .2s;display:flex;align-items:center;justify-content:center;gap:6px;
}
.help-tab:hover{color:#e1306c;background:#fdf0f8}
.help-tab.active{color:#e1306c;border-bottom-color:#e1306c;background:#fff}

/* ── GUIDE TAB ── */
.help-guide{padding:14px 16px;overflow-y:auto;display:none;flex-direction:column;gap:0}
.help-guide.active{display:flex}
.help-step{
    display:flex;gap:12px;padding:10px 0;
    border-bottom:1px solid #fdf0f8;align-items:flex-start;
}
.help-step:last-of-type{border-bottom:none}
.help-step-num{
    width:24px;height:24px;border-radius:50%;
    background:var(--ig-gradient);color:#fff;
    font-size:11px;font-weight:700;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;margin-top:1px;
}
.help-step-text strong{display:block;font-size:13px;color:#1a0a2e;font-weight:600;margin-bottom:2px}
.help-step-text span{font-size:12px;color:#6b7280;line-height:1.5}
.help-links{display:flex;flex-direction:column;gap:6px;margin-top:12px;padding-top:12px;border-top:1px solid #f5e6f0}
.help-link{
    display:flex;align-items:center;gap:10px;
    padding:9px 12px;background:#fdf0f8;
    border-radius:10px;font-size:13px;color:#1a0a2e;
    font-weight:500;transition:all .2s;
}
.help-link:hover{background:#f0d6e8;transform:translateX(2px)}
.help-link i{color:#e1306c;width:14px;text-align:center;font-size:13px}

/* ── CHAT TAB ── */
.chat-panel{display:none;flex-direction:column;height:400px;min-height:0}
.chat-panel.active{display:flex}

.chat-messages{
    flex:1;overflow-y:auto;padding:14px 14px 8px;
    display:flex;flex-direction:column;gap:10px;
    background:#f8f4fb;
    scroll-behavior:smooth;
}
.chat-messages::-webkit-scrollbar{width:4px}
.chat-messages::-webkit-scrollbar-track{background:transparent}
.chat-messages::-webkit-scrollbar-thumb{background:#e0c8e8;border-radius:2px}

/* Message bubbles */
.chat-msg{
    max-width:82%;padding:10px 14px;
    border-radius:18px;font-size:13px;
    line-height:1.55;word-break:break-word;
    box-shadow:0 1px 4px rgba(0,0,0,.08);
}
.chat-msg.user{
    background:linear-gradient(135deg,#e1306c,#bc1888);
    color:#fff;align-self:flex-end;
    border-bottom-right-radius:4px;
}
.chat-msg.admin{
    background:#fff;color:#1a0a2e;
    align-self:flex-start;
    border:1px solid #f0d6e8;
    border-bottom-left-radius:4px;
}
.chat-msg.admin .msg-sender{
    font-size:10px;font-weight:700;
    color:#e1306c;margin-bottom:4px;
    display:flex;align-items:center;gap:4px;
}
.chat-msg .msg-time{
    font-size:10px;opacity:.55;
    margin-top:4px;text-align:right;
    display:block;
}
.chat-msg.admin .msg-time{text-align:left}

/* Typing indicator */
.chat-typing{
    display:flex;align-items:center;gap:4px;
    padding:8px 14px;background:#fff;
    border:1px solid #f0d6e8;border-radius:18px;border-bottom-left-radius:4px;
    align-self:flex-start;box-shadow:0 1px 4px rgba(0,0,0,.06);
}
.chat-typing span{
    width:7px;height:7px;border-radius:50%;
    background:#e1306c;display:inline-block;
    animation:typingDot 1.2s infinite;
}
.chat-typing span:nth-child(2){animation-delay:.2s}
.chat-typing span:nth-child(3){animation-delay:.4s}
@keyframes typingDot{0%,60%,100%{transform:translateY(0);opacity:.4}30%{transform:translateY(-5px);opacity:1}}

.chat-empty{
    flex:1;display:flex;flex-direction:column;
    align-items:center;justify-content:center;
    color:#b09ab8;font-size:13px;text-align:center;gap:10px;
    padding:20px;
}
.chat-empty i{font-size:36px;color:#e8d0f0}
.chat-empty p{line-height:1.6;color:#9ca3af}

/* Input row */
.chat-input-row{
    display:flex;align-items:center;gap:8px;
    padding:10px 12px;
    border-top:1px solid #f0d6e8;
    background:#fff;
    flex-shrink:0;
}
.chat-input-row input{
    flex:1;border:1.5px solid #f0d6e8;
    border-radius:22px;padding:9px 16px;
    font-size:13px;outline:none;
    font-family:inherit;background:#fdf8ff;
    color:#1a0a2e;transition:border-color .2s;
}
.chat-input-row input::placeholder{color:#c4a8cc}
.chat-input-row input:focus{border-color:#e1306c;background:#fff;box-shadow:0 0 0 3px rgba(225,48,108,.08)}
.chat-send-btn{
    width:38px;height:38px;border-radius:50%;
    background:var(--ig-gradient);color:#fff;
    border:none;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    font-size:14px;flex-shrink:0;
    transition:transform .2s,box-shadow .2s;
    box-shadow:0 2px 8px rgba(225,48,108,.3);
}
.chat-send-btn:hover{transform:scale(1.08);box-shadow:0 4px 14px rgba(225,48,108,.45)}
.chat-send-btn:active{transform:scale(.95)}

/* Login notice */
.chat-login-notice{
    flex:1;display:flex;flex-direction:column;
    align-items:center;justify-content:center;
    padding:30px 20px;text-align:center;
    color:#6b7280;font-size:13px;gap:12px;
    background:#f8f4fb;
}
.chat-login-notice i{font-size:36px;color:#e8d0f0}
.chat-login-notice a{color:#e1306c;font-weight:600;text-decoration:underline}

/* Mobile adjustments */
@media(max-width:480px){
    .help-popup{width:calc(100vw - 24px);right:12px;bottom:84px;border-radius:16px}
    .chat-panel{height:340px}
}
</style>

<div class="help-fab">
    <div class="help-popup" id="helpPopup">

        {{-- HEADER --}}
        <div class="help-header">
            <div class="help-header-text">
                <h3><i class="fas fa-headset"></i> PraiseStore Help</h3>
                <p>We typically reply instantly 🤖</p>
            </div>
            <button class="help-close-btn" onclick="toggleHelp()"><i class="fas fa-times"></i></button>
        </div>

        {{-- TABS --}}
        <div class="help-tabs">
            <div class="help-tab active" onclick="switchHelpTab('guide')" id="tab-btn-guide">
                <i class="fas fa-book-open"></i> Guide
            </div>
            <div class="help-tab" onclick="switchHelpTab('chat')" id="tab-btn-chat">
                <i class="fas fa-comments"></i> Live Chat
            </div>
        </div>

        {{-- GUIDE TAB --}}
        <div class="help-guide active" id="tab-guide">
            <div class="help-step">
                <div class="help-step-num">1</div>
                <div class="help-step-text"><strong>Browse &amp; Search</strong><span>Use the search bar or browse categories to find clothing and accessories.</span></div>
            </div>
            <div class="help-step">
                <div class="help-step-num">2</div>
                <div class="help-step-text"><strong>Add to Cart</strong><span>Click "Add to Cart" on any product. View your cart anytime from the top bar.</span></div>
            </div>
            <div class="help-step">
                <div class="help-step-num">3</div>
                <div class="help-step-text"><strong>Checkout</strong><span>Fill in your delivery details and choose Mobile Money or Cash on Delivery.</span></div>
            </div>
            <div class="help-step">
                <div class="help-step-num">4</div>
                <div class="help-step-text"><strong>Track Your Order</strong><span>Use your order number in My Account → My Orders to track delivery.</span></div>
            </div>
            <div class="help-step">
                <div class="help-step-num">5</div>
                <div class="help-step-text"><strong>Payment Methods</strong><span>We accept Mobile Money (MoMo) and Cash on Delivery across Rwanda.</span></div>
            </div>
            <div class="help-links">
                <a href="{{ route('shop') }}" class="help-link"><i class="fas fa-store"></i> Browse Shop</a>
                <a href="{{ route('cart') }}" class="help-link"><i class="fas fa-shopping-bag"></i> View Cart</a>
                @auth
                <a href="{{ route('customer.orders') }}" class="help-link"><i class="fas fa-box"></i> My Orders</a>
                @else
                <a href="{{ route('login') }}" class="help-link"><i class="fas fa-user"></i> Login / Register</a>
                @endauth
            </div>
        </div>

        {{-- CHAT TAB --}}
        <div class="chat-panel" id="tab-chat">
            @auth
            <div class="chat-messages" id="chatMessages">
                <div class="chat-empty">
                    <i class="fas fa-comments"></i>
                    <p>No messages yet.<br>Ask us anything about your order,<br>products, or delivery!</p>
                </div>
            </div>
            <div class="chat-input-row">
                <input type="text" id="chatInput" placeholder="Ask something..." onkeydown="if(event.key==='Enter')sendChat()" autocomplete="off">
                <button class="chat-send-btn" onclick="sendChat()"><i class="fas fa-paper-plane"></i></button>
            </div>
            @else
            <div class="chat-login-notice">
                <i class="fas fa-lock"></i>
                <p>Please <a href="{{ route('login') }}">log in</a> to chat<br>with our support team.</p>
            </div>
            @endauth
        </div>

    </div>

    <button class="help-fab-btn" onclick="toggleHelp()" title="Help &amp; Support">
        <i class="fas fa-question" id="helpIcon"></i>
        <span class="help-fab-badge" id="chatBadge"></span>
    </button>
</div>

<script>
let helpOpen = false;
let currentTab = 'guide';
let chatPolling = null;

function toggleHelp() {
    helpOpen = !helpOpen;
    document.getElementById('helpPopup').classList.toggle('open', helpOpen);
    document.getElementById('helpIcon').className = helpOpen ? 'fas fa-times' : 'fas fa-question';
    if (helpOpen && currentTab === 'chat') { loadChat(); startChatPolling(); }
    if (!helpOpen) stopChatPolling();
}

function switchHelpTab(tab) {
    currentTab = tab;
    document.getElementById('tab-btn-guide').classList.toggle('active', tab === 'guide');
    document.getElementById('tab-btn-chat').classList.toggle('active', tab === 'chat');
    document.getElementById('tab-guide').classList.toggle('active', tab === 'guide');
    document.getElementById('tab-chat').classList.toggle('active', tab === 'chat');
    if (tab === 'chat') { loadChat(); startChatPolling(); }
    else stopChatPolling();
}

@auth
function loadChat() {
    fetch('{{ route("chat.fetch") }}')
        .then(r => r.json())
        .then(msgs => renderChat(msgs));
}

function renderChat(msgs) {
    const box = document.getElementById('chatMessages');
    if (!msgs.length) {
        box.innerHTML = '<div class="chat-empty"><i class="fas fa-comments"></i><p>No messages yet.<br>Ask us anything about your order,<br>products, or delivery!</p></div>';
        return;
    }
    const wasAtBottom = box.scrollHeight - box.scrollTop <= box.clientHeight + 40;
    box.innerHTML = msgs.map(m => {
        const isAdmin = m.is_admin;
        return '<div class="chat-msg ' + (isAdmin ? 'admin' : 'user') + '">' +
            (isAdmin ? '<div class="msg-sender"><i class="fas fa-robot" style="font-size:9px"></i> PraiseStore Bot</div>' : '') +
            escHtml(m.message) +
            '<span class="msg-time">' + formatTime(m.created_at) + '</span>' +
            '</div>';
    }).join('');
    if (wasAtBottom) box.scrollTop = box.scrollHeight;
}

function sendChat() {
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg) return;
    input.value = '';
    input.disabled = true;

    // Optimistic user bubble
    const box = document.getElementById('chatMessages');
    const emptyEl = box.querySelector('.chat-empty');
    if (emptyEl) emptyEl.remove();
    const userBubble = document.createElement('div');
    userBubble.className = 'chat-msg user';
    userBubble.innerHTML = escHtml(msg) + '<span class="msg-time">' + formatTime(new Date().toISOString()) + '</span>';
    box.appendChild(userBubble);
    box.scrollTop = box.scrollHeight;

    // Show typing indicator
    const typing = document.createElement('div');
    typing.className = 'chat-typing';
    typing.id = 'typingIndicator';
    typing.innerHTML = '<span></span><span></span><span></span>';
    box.appendChild(typing);
    box.scrollTop = box.scrollHeight;

    fetch('{{ route("chat.send") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({message: msg})
    }).then(() => {
        setTimeout(() => {
            const t = document.getElementById('typingIndicator');
            if (t) t.remove();
            loadChat();
            input.disabled = false;
            input.focus();
        }, 600);
    }).catch(() => { input.disabled = false; });
}

function startChatPolling() {
    stopChatPolling();
    chatPolling = setInterval(loadChat, 4000);
}
function stopChatPolling() {
    if (chatPolling) { clearInterval(chatPolling); chatPolling = null; }
}

setInterval(() => {
    fetch('{{ route("chat.unread") }}')
        .then(r => r.json())
        .then(d => {
            const badge = document.getElementById('chatBadge');
            if (d.count > 0) { badge.style.display = 'flex'; badge.textContent = d.count; }
            else { badge.style.display = 'none'; }
        });
}, 8000);
@endauth

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function formatTime(ts) {
    return new Date(ts).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
}

// Mobile nav
function openMobileNav(){
    document.getElementById('mobileNav').classList.add('open');
    document.getElementById('navOverlay').classList.add('open');
    document.body.style.overflow='hidden';
}
function closeMobileNav(){
    document.getElementById('mobileNav').classList.remove('open');
    document.getElementById('navOverlay').classList.remove('open');
    document.body.style.overflow='';
}
document.addEventListener('keydown', e => { if(e.key==='Escape'){ closeMobileNav(); if(helpOpen) toggleHelp(); } });
</script>
</body>
</html>
