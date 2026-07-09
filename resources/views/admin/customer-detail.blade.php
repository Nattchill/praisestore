@extends('admin.layout')
@section('title', $user->name)
@section('page-title', 'Customer Detail')
@section('breadcrumb', 'Customers / ' . $user->name)

@push('styles')
<style>
    .detail-grid{display:grid;grid-template-columns:300px 1fr;gap:20px}
    .profile-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px;text-align:center;height:fit-content}
    .profile-avatar{width:80px;height:80px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:#fff;margin:0 auto 14px}
    .profile-name{font-size:18px;font-weight:700;margin-bottom:4px}
    .profile-email{font-size:13px;color:var(--gray);margin-bottom:16px}
    .profile-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:16px}
    .profile-stat{background:var(--light);border-radius:8px;padding:12px;text-align:center}
    .profile-stat .val{font-size:18px;font-weight:800;color:var(--dark)}
    .profile-stat .lbl{font-size:11px;color:var(--gray);margin-top:2px}
    @media(max-width:900px){.detail-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div style="margin-bottom:16px">
    <a href="{{ route('admin.customers') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Customers</a>
</div>

<div class="detail-grid">
    <div>
        <div class="profile-card">
            <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-email">{{ $user->email }}</div>
            <span class="badge {{ $user->email_verified_at ? 'badge-active' : 'badge-inactive' }}">
                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
            </span>
            <div class="profile-stats">
                <div class="profile-stat">
                    <div class="val">{{ $orders->count() }}</div>
                    <div class="lbl">Orders</div>
                </div>
                <div class="profile-stat">
                    <div class="val">RWF {{ number_format($totalSpent) }}</div>
                    <div class="lbl">Total Spent</div>
                </div>
            </div>
            <div style="margin-top:16px;font-size:12px;color:var(--gray)">
                <i class="fas fa-calendar"></i> Joined {{ $user->created_at->format('d M Y') }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Order History</h2>
            <span style="font-size:13px;color:var(--gray)">{{ $orders->count() }} orders</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Order #</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td><strong>RWF {{ number_format($order->total) }}</strong></td>
                        <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td style="color:var(--gray)">{{ $order->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--gray);padding:30px">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
