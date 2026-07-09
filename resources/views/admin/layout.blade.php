<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – PraiseStore</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{
            --primary:#2563eb;--primary-dark:#1d4ed8;
            --dark:#0f172a;--sidebar:#0f172a;--sidebar-hover:#1e293b;
            --border:#e2e8f0;--gray:#64748b;--light:#f0f7ff;--white:#fff;
            --success:#10b981;--danger:#ef4444;--info:#3b82f6;--warning:#f59e0b;
        }
        body{font-family:'Inter',sans-serif;background:var(--light);display:flex;min-height:100vh;color:#1e293b}
        a{text-decoration:none;color:inherit}
        img{max-width:100%}

        /* ── SIDEBAR ── */
        .sidebar{
            width:250px;background:var(--sidebar);color:#fff;
            display:flex;flex-direction:column;
            position:fixed;top:0;left:0;height:100vh;z-index:200;
            transition:transform .3s ease;overflow:hidden;
        }
        .sidebar-logo{
            padding:18px 20px;border-bottom:1px solid rgba(255,255,255,.08);
            display:flex;align-items:center;gap:12px;flex-shrink:0;
        }
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--primary-dark),var(--primary));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
        .logo-text{font-size:16px;font-weight:800;letter-spacing:-.3px}
        .logo-text span{color:var(--primary)}
        .sidebar-nav{flex:1;padding:10px 0;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent}
        .nav-label{padding:12px 20px 5px;font-size:10px;font-weight:700;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:1px}
        .nav-item{display:flex;align-items:center;gap:11px;padding:9px 20px;font-size:13px;font-weight:500;color:rgba(255,255,255,.6);transition:all .2s;cursor:pointer;border-left:3px solid transparent;position:relative}
        .nav-item:hover{background:var(--sidebar-hover);color:#fff;border-left-color:rgba(37,99,235,.5)}
        .nav-item.active{background:rgba(37,99,235,.2);color:#fff;border-left-color:var(--primary)}
        .nav-item i{width:16px;text-align:center;font-size:13px;flex-shrink:0}
        .nav-badge{margin-left:auto;background:var(--primary);color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:20px;flex-shrink:0}
        .sidebar-footer{padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .admin-info{display:flex;align-items:center;gap:10px}
        .admin-avatar{width:32px;height:32px;background:linear-gradient(135deg,var(--primary-dark),var(--primary));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
        .admin-name{font-size:13px;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .admin-role{font-size:11px;color:rgba(255,255,255,.4)}

        /* Sidebar overlay */
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:199}
        .sidebar-overlay.open{display:block}

        /* ── MAIN ── */
        .main{margin-left:250px;flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0}

        /* ── TOPBAR ── */
        .topbar{
            background:var(--white);border-bottom:1px solid var(--border);
            padding:0 20px;height:60px;display:flex;align-items:center;
            justify-content:space-between;position:sticky;top:0;z-index:100;flex-shrink:0;
        }
        .topbar-left{display:flex;align-items:center;gap:12px;min-width:0}
        .menu-toggle-btn{background:none;border:none;font-size:20px;cursor:pointer;color:var(--gray);padding:4px;flex-shrink:0;display:none}
        .page-title{font-size:16px;font-weight:700;color:var(--dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .breadcrumb{font-size:11px;color:var(--gray);display:flex;align-items:center;gap:4px;flex-wrap:wrap}
        .breadcrumb a{color:var(--primary)}
        .topbar-right{display:flex;align-items:center;gap:8px;flex-shrink:0}
        .topbar-store{display:flex;align-items:center;gap:6px;padding:7px 12px;background:linear-gradient(135deg,var(--primary-dark),var(--primary));color:#fff;border-radius:8px;font-size:12px;font-weight:600;white-space:nowrap}
        .topbar-store:hover{opacity:.9}

        /* ── CONTENT ── */
        .content{padding:20px 24px;flex:1;min-width:0}

        /* ── FLASH ── */
        .flash{padding:11px 14px;border-radius:8px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:10px}
        .flash.success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
        .flash.error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}

        /* ── CARDS ── */
        .card{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:20px}
        .card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:8px}
        .card-header h2{font-size:14px;font-weight:700;color:var(--dark)}

        /* ── BUTTONS ── */
        .btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:12.5px;font-weight:600;cursor:pointer;border:none;transition:all .2s;white-space:nowrap}
        .btn-primary{background:linear-gradient(135deg,var(--primary-dark),var(--primary));color:#fff}
        .btn-primary:hover{opacity:.9}
        .btn-secondary{background:var(--light);color:var(--dark);border:1px solid var(--border)}
        .btn-secondary:hover{background:var(--border)}
        .btn-danger{background:var(--danger);color:#fff}
        .btn-danger:hover{background:#b91c1c}
        .btn-success{background:var(--success);color:#fff}
        .btn-info{background:var(--info);color:#fff}
        .btn-sm{padding:5px 10px;font-size:11.5px;border-radius:6px}
        .btn-icon{width:30px;height:30px;padding:0;justify-content:center;border-radius:6px}

        /* ── TABLE ── */
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
        table{width:100%;border-collapse:collapse;min-width:500px}
        th{background:#f8fafc;padding:9px 12px;text-align:left;font-size:11px;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
        td{padding:10px 12px;border-top:1px solid var(--border);font-size:12.5px;vertical-align:middle}
        tr:hover td{background:#fafbfc}

        /* ── BADGES ── */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700}
        .badge-pending{background:#fef3c7;color:#92400e}
        .badge-processing{background:#dbeafe;color:#1e40af}
        .badge-shipped{background:#e0e7ff;color:#3730a3}
        .badge-delivered{background:#d1fae5;color:#065f46}
        .badge-cancelled{background:#fee2e2;color:#991b1b}
        .badge-active{background:#d1fae5;color:#065f46}
        .badge-inactive{background:#f3f4f6;color:#6b7280}

        /* ── FORMS ── */
        .form-group{margin-bottom:14px}
        .form-group label{display:block;font-size:12px;font-weight:600;color:var(--dark);margin-bottom:5px}
        .form-control{width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;outline:none;transition:border-color .2s;font-family:inherit;background:#fff;color:var(--dark)}
        .form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        .form-error{color:var(--danger);font-size:11px;margin-top:4px}

        /* ── MODAL ── */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:300;display:none;align-items:center;justify-content:center;padding:16px}
        .modal-overlay.open{display:flex}
        .modal{background:#fff;border-radius:14px;padding:24px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto}
        .modal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
        .modal-header h3{font-size:15px;font-weight:700}
        .modal-close{background:none;border:none;font-size:20px;cursor:pointer;color:var(--gray);line-height:1}
        .modal-close:hover{color:var(--dark)}

        /* ── PAGINATION ── */
        .pagination-wrap{margin-top:16px;display:flex;justify-content:flex-end;flex-wrap:wrap;gap:4px}

        /* ── RESPONSIVE ── */
        @media(max-width:1024px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.open{transform:translateX(0)}
            .main{margin-left:0}
            .menu-toggle-btn{display:block}
            .topbar{padding:0 16px}
            .content{padding:16px}
            .topbar-store span{display:none}
        }
        @media(max-width:640px){
            .content{padding:12px}
            .card{padding:14px}
            .card-header{flex-direction:column;align-items:flex-start}
            .form-row{grid-template-columns:1fr}
            .topbar{height:54px}
            .page-title{font-size:14px}
            th,td{padding:8px 10px;font-size:11.5px}
            .btn-sm{padding:4px 8px;font-size:11px}
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-store"></i></div>
        <div class="logo-text">Praise<span>Store</span></div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        <div class="nav-label">Catalog</div>
        <a href="{{ route('admin.products') }}" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="fas fa-tshirt"></i> Products
            <span class="nav-badge">{{ \App\Models\Product::count() }}</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Categories
            <span class="nav-badge">{{ \App\Models\Category::count() }}</span>
        </a>

        <div class="nav-label">People</div>
        <a href="{{ route('admin.customers') }}" class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Customers
            <span class="nav-badge">{{ \App\Models\User::where('is_admin',false)->count() }}</span>
        </a>

        <div class="nav-label">Sales</div>
        <a href="{{ route('admin.orders') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> Orders
            @php $pendingCount = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pendingCount > 0)<span class="nav-badge">{{ $pendingCount }}</span>@endif
        </a>
        <a href="{{ route('admin.payments') }}" class="nav-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> Payments
            @php $pendingMomo = \App\Models\Order::where('payment_method','mobile_money')->where('payment_status','pending')->count(); @endphp
            @if($pendingMomo > 0)<span class="nav-badge" style="background:#f59e0b">{{ $pendingMomo }}</span>@endif
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Reports
        </a>

        <div class="nav-label">System</div>
        <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Settings
        </a>
        <div class="nav-item" onclick="toggleAdminChat()" style="cursor:pointer">
            <i class="fas fa-comments"></i> Live Chat
            <span class="nav-badge" id="sidebarChatBadge" style="display:none">0</span>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="nav-item">
            <i class="fas fa-external-link-alt"></i> View Store
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="admin-name">{{ auth()->user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
    </div>
</aside>

{{-- SIDEBAR OVERLAY --}}
<div class="sidebar-overlay" id="adminSidebarOverlay" onclick="closeSidebar()"></div>

{{-- MAIN --}}
<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle-btn" onclick="toggleSidebar()" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <div class="page-title">@yield('page-title', 'Dashboard')</div>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                    <i class="fas fa-chevron-right" style="font-size:9px"></i>
                    @yield('breadcrumb', 'Dashboard')
                </div>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('home') }}" target="_blank" class="topbar-store">
                <i class="fas fa-store"></i> <span>View Store</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-sign-out-alt"></i> <span class="hide-xs">Logout</span></button>
            </form>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="flash success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

{{-- ADMIN LIVE CHAT PANEL --}}
<style>
.admin-chat-fab{position:fixed;bottom:28px;right:28px;z-index:9999}
.admin-chat-btn{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--primary-dark),var(--primary));color:#fff;border:none;font-size:20px;cursor:pointer;box-shadow:0 4px 16px rgba(37,99,235,.4);display:flex;align-items:center;justify-content:center;position:relative;transition:transform .2s}
.admin-chat-btn:hover{transform:scale(1.08)}
.admin-chat-badge{position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;width:18px;height:18px;border-radius:50%;display:none;align-items:center;justify-content:center;border:2px solid #fff}
.admin-chat-panel{position:fixed;bottom:90px;right:16px;width:min(680px,calc(100vw - 32px));height:min(480px,calc(100vh - 120px));background:#fff;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,.18);z-index:9998;display:none;overflow:hidden;flex-direction:row}
.admin-chat-panel.open{display:flex}
.chat-sidebar{width:200px;border-right:1px solid var(--border);display:flex;flex-direction:column;background:#f8fafc}
.chat-sidebar-header{padding:14px 16px;font-size:13px;font-weight:700;color:var(--dark);border-bottom:1px solid var(--border);background:#fff}
.chat-user-list{flex:1;overflow-y:auto}
.chat-user-item{padding:12px 14px;cursor:pointer;border-bottom:1px solid var(--border);transition:background .15s}
.chat-user-item:hover,.chat-user-item.active{background:#eff6ff}
.chat-user-name{font-size:13px;font-weight:600;color:var(--dark)}
.chat-user-preview{font-size:11px;color:var(--gray);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.chat-user-unread{background:var(--primary);color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;float:right;margin-top:2px}
.chat-main{flex:1;display:flex;flex-direction:column}
.chat-main-header{padding:12px 16px;border-bottom:1px solid var(--border);font-size:13px;font-weight:700;color:var(--dark);background:#fff;display:flex;align-items:center;justify-content:space-between}
.admin-chat-messages{flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:8px;background:#f8fafc}
.admin-chat-msg{max-width:75%;padding:8px 12px;border-radius:12px;font-size:13px;line-height:1.5;word-break:break-word}
.admin-chat-msg.from-user{background:#fff;border:1px solid var(--border);align-self:flex-start;border-bottom-left-radius:3px}
.admin-chat-msg.from-admin{background:linear-gradient(135deg,var(--primary-dark),var(--primary));color:#fff;align-self:flex-end;border-bottom-right-radius:3px}
.admin-chat-msg .msg-time{font-size:10px;opacity:.6;margin-top:3px;text-align:right}
.admin-chat-input{display:flex;gap:8px;padding:10px 12px;border-top:1px solid var(--border);background:#fff}
.admin-chat-input input{flex:1;border:1px solid var(--border);border-radius:20px;padding:8px 14px;font-size:13px;outline:none;font-family:inherit}
.admin-chat-input input:focus{border-color:var(--primary)}
.admin-chat-send{padding:8px 16px;background:linear-gradient(135deg,var(--primary-dark),var(--primary));color:#fff;border:none;border-radius:20px;font-size:13px;font-weight:600;cursor:pointer}
@media(max-width:540px){.admin-chat-panel{flex-direction:column}.chat-sidebar{width:100%;height:140px;border-right:none;border-bottom:1px solid var(--border)}.hide-xs{display:none}}
.bot-active-bar{padding:10px 16px;background:#ecfdf5;border-top:1px solid #a7f3d0;color:#065f46;font-size:12px;font-weight:600;display:flex;align-items:center;gap:8px;flex-shrink:0}
.bot-label{font-size:10px;font-weight:700;color:rgba(255,255,255,.7);margin-bottom:3px;display:flex;align-items:center;gap:4px}
</style>

<div class="admin-chat-fab">
    <div class="admin-chat-panel" id="adminChatPanel">
        <div class="chat-sidebar">
            <div class="chat-sidebar-header"><i class="fas fa-comments"></i> Conversations</div>
            <div class="chat-user-list" id="adminUserList"><div style="padding:16px;font-size:12px;color:#9ca3af">Loading...</div></div>
        </div>
        <div class="chat-main">
            <div class="chat-main-header">
                <span id="adminChatTitle">Select a conversation</span>
                <button onclick="toggleAdminChat()" style="background:none;border:none;cursor:pointer;color:#9ca3af;font-size:16px"><i class="fas fa-times"></i></button>
            </div>
            <div id="adminChatBody">
                <div class="chat-no-select"><i class="fas fa-comments" style="font-size:32px;color:#e2e8f0"></i>Select a user to view messages</div>
            </div>
        </div>
    </div>
    <button class="admin-chat-btn" onclick="toggleAdminChat()" title="Live Chat">
        <i class="fas fa-comments" id="adminChatIcon"></i>
        <span class="admin-chat-badge" id="adminChatBadge"></span>
    </button>
</div>

<script>
let adminChatOpen = false;
let selectedUserId = null;
let adminChatPolling = null;
let adminConvPolling = null;

function toggleAdminChat() {
    adminChatOpen = !adminChatOpen;
    document.getElementById('adminChatPanel').classList.toggle('open', adminChatOpen);
    document.getElementById('adminChatIcon').className = adminChatOpen ? 'fas fa-times' : 'fas fa-comments';
    if (adminChatOpen) { loadConversations(); adminConvPolling = setInterval(loadConversations, 5000); }
    else { clearInterval(adminConvPolling); clearInterval(adminChatPolling); }
}

function loadConversations() {
    fetch('/admin/chat/conversations')
        .then(r => r.json())
        .then(users => {
            const list = document.getElementById('adminUserList');
            if (!users.length) { list.innerHTML = '<div style="padding:16px;font-size:12px;color:#9ca3af">No conversations yet.</div>'; return; }
            list.innerHTML = users.map(u =>
                '<div class="chat-user-item' + (selectedUserId == u.user_id ? ' active' : '') + '" onclick="selectUser(' + u.user_id + ',\'' + escHtml(u.name) + '\')">' +
                (u.unread > 0 ? '<span class="chat-user-unread">' + u.unread + '</span>' : '') +
                '<div class="chat-user-name">' + escHtml(u.name) + '</div>' +
                '<div class="chat-user-preview">' + escHtml(u.email) + '</div></div>'
            ).join('');
        });
}

function selectUser(userId, name) {
    selectedUserId = userId;
    document.getElementById('adminChatTitle').textContent = name;
    document.getElementById('adminChatBody').innerHTML =
        '<div class="admin-chat-messages" id="adminMessages"></div>' +
        '<div class="bot-active-bar"><i class="fas fa-robot"></i> Auto-bot is handling all replies</div>';
    loadAdminMessages();
    clearInterval(adminChatPolling);
    adminChatPolling = setInterval(loadAdminMessages, 4000);
    loadConversations();
}

function loadAdminMessages() {
    if (!selectedUserId) return;
    fetch('/admin/chat/fetch/' + selectedUserId)
        .then(r => r.json())
        .then(msgs => {
            const box = document.getElementById('adminMessages');
            if (!box) return;
            if (!msgs.length) { box.innerHTML = '<div style="text-align:center;color:#9ca3af;padding:20px;font-size:13px">No messages yet.</div>'; return; }
            box.innerHTML = msgs.map(m =>
                '<div class="admin-chat-msg ' + (m.is_admin ? 'from-admin' : 'from-user') + '">' +
                (m.is_admin ? '<div class="bot-label"><i class="fas fa-robot"></i> Bot</div>' : '') +
                escHtml(m.message) +
                '<div class="msg-time">' + formatTime(m.created_at) + '</div></div>'
            ).join('');
            box.scrollTop = box.scrollHeight;
        });
}

// Unread badge polling
setInterval(() => {
    fetch('/admin/chat/unread')
        .then(r => r.json())
        .then(d => {
            const badge = document.getElementById('adminChatBadge');
            if (d.count > 0) { badge.style.display = 'flex'; badge.textContent = d.count;
            const sb = document.getElementById('sidebarChatBadge');
            if(sb){sb.style.display='inline-flex';sb.textContent=d.count;}
        }
        else { badge.style.display = 'none';
            const sb = document.getElementById('sidebarChatBadge');
            if(sb) sb.style.display='none';
        }
        });
}, 6000);

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function formatTime(ts) {
    return new Date(ts).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
}
</script>

<script>
function toggleSidebar(){
    const s=document.getElementById('sidebar');
    const o=document.getElementById('adminSidebarOverlay');
    const open=s.classList.toggle('open');
    o.classList.toggle('open',open);
    document.body.style.overflow=open?'hidden':'';
}
function closeSidebar(){
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('adminSidebarOverlay').classList.remove('open');
    document.body.style.overflow='';
}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeSidebar();});
</script>
@stack('scripts')
</body>
</html>
