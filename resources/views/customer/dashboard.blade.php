@extends('customer.layout')
@section('title', 'My Dashboard')

@push('styles')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
    .stat-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px;text-align:center}
    .stat-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;margin:0 auto 10px}
    .stat-val{font-size:22px;font-weight:800;color:var(--dark)}
    .stat-lbl{font-size:12px;color:var(--gray);margin-top:3px}
    .welcome-banner{background:linear-gradient(135deg,var(--dark),#16213e);border-radius:14px;padding:24px 28px;color:#fff;display:flex;align-items:center;justify-content:space-between;margin-bottom:4px}
    .welcome-banner h2{font-size:20px;font-weight:800;margin-bottom:6px}
    .welcome-banner p{font-size:13px;color:rgba(255,255,255,.7)}
    .quick-actions{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .quick-action{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center;transition:all .2s;cursor:pointer}
    .quick-action:hover{border-color:var(--primary);box-shadow:0 4px 16px rgba(245,166,35,.15);transform:translateY(-2px)}
    .quick-action i{font-size:24px;color:var(--primary);margin-bottom:8px;display:block}
    .quick-action span{font-size:13px;font-weight:600;color:var(--dark)}
    @media(max-width:700px){.stats-grid{grid-template-columns:repeat(2,1fr)}.quick-actions{grid-template-columns:1fr 1fr}.welcome-banner{flex-direction:column;gap:14px;text-align:center}}
</style>
@endpush

@section('content')

{{-- WELCOME BANNER --}}
<div class="welcome-banner">
    <div>
        <h2>Welcome back, {{ $user->name }}! 👋</h2>
        <p>Here's a summary of your account activity.</p>
    </div>
    <a href="{{ route('shop') }}" class="btn btn-primary"><i class="fas fa-tshirt"></i> Shop Now</a>
</div>

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-shopping-bag"></i></div>
        <div class="stat-val">{{ $stats['total_orders'] }}</div>
        <div class="stat-lbl">Total Orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#92400e"><i class="fas fa-clock"></i></div>
        <div class="stat-val">{{ $stats['pending'] }}</div>
        <div class="stat-lbl">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;color:#065f46"><i class="fas fa-check-circle"></i></div>
        <div class="stat-val">{{ $stats['delivered'] }}</div>
        <div class="stat-lbl">Delivered</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff7ed;color:var(--primary)"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-val" style="font-size:16px">RWF {{ number_format($stats['total_spent']) }}</div>
        <div class="stat-lbl">Total Spent</div>
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div class="card">
    <div class="card-header"><h2><i class="fas fa-bolt"></i> Quick Actions</h2></div>
    <div class="quick-actions">
        <a href="{{ route('shop') }}" class="quick-action">
            <i class="fas fa-tshirt"></i>
            <span>Browse Shop</span>
        </a>
        <a href="{{ route('cart') }}" class="quick-action">
            <i class="fas fa-shopping-cart"></i>
            <span>View Cart</span>
        </a>
        <a href="{{ route('customer.orders') }}" class="quick-action">
            <i class="fas fa-list-alt"></i>
            <span>My Orders</span>
        </a>
        <a href="{{ route('customer.profile') }}" class="quick-action">
            <i class="fas fa-user-edit"></i>
            <span>Edit Profile</span>
        </a>
        <a href="{{ route('checkout') }}" class="quick-action">
            <i class="fas fa-credit-card"></i>
            <span>Checkout</span>
        </a>
        <a href="{{ route('customer.orders') }}#track" class="quick-action">
            <i class="fas fa-map-marker-alt"></i>
            <span>Track Order</span>
        </a>
    </div>
</div>

{{-- RECENT ORDERS --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-history"></i> Recent Orders</h2>
        <a href="{{ route('customer.orders') }}" class="btn btn-secondary btn-sm">View All</a>
    </div>
    @if($orders->isEmpty())
        <div style="text-align:center;padding:40px 20px;color:var(--gray)">
            <i class="fas fa-shopping-bag" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px"></i>
            <p style="font-weight:600;margin-bottom:8px">No orders yet</p>
            <a href="{{ route('shop') }}" class="btn btn-primary btn-sm"><i class="fas fa-tshirt"></i> Start Shopping</a>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Order #</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->items->count() }} item(s)</td>
                    <td><strong>RWF {{ number_format($order->total) }}</strong></td>
                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="color:var(--gray)">{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('customer.track', $order->order_number) }}" class="btn btn-secondary btn-sm"><i class="fas fa-map-marker-alt"></i> Track</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
