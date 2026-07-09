@extends('admin.layout')
@section('title', 'Payments')
@section('page-title', 'Payment Management')
@section('breadcrumb', 'Payments')

@push('styles')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
    .stat-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px}
    .stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
    .stat-icon.blue{background:#eff6ff;color:#2563eb}
    .stat-icon.green{background:#f0fdf4;color:#16a34a}
    .stat-icon.yellow{background:#fefce8;color:#ca8a04}
    .stat-icon.purple{background:#f5f3ff;color:#7c3aed}
    .stat-value{font-size:20px;font-weight:800;color:#0f172a}
    .stat-label{font-size:12px;color:var(--gray);margin-top:2px}

    .filter-bar{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center}
    .filter-tab{padding:7px 16px;border-radius:20px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid var(--border);background:#fff;color:var(--gray);transition:all .2s;text-decoration:none}
    .filter-tab:hover,.filter-tab.active{background:var(--primary);color:#fff;border-color:var(--primary)}

    .badge-paid{background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .badge-pending{background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .badge-failed{background:#fee2e2;color:#b91c1c;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .badge-momo{background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
    .badge-cod{background:#f0fdf4;color:#15803d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}

    .momo-phone{display:inline-flex;align-items:center;gap:5px;background:#f0f7ff;color:#1d4ed8;padding:3px 10px;border-radius:6px;font-size:12px;font-weight:600}

    @media(max-width:900px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:480px){.stats-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-value">RWF {{ number_format($stats['total_paid']) }}</div>
            <div class="stat-label">Total Confirmed Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pending_momo'] }}</div>
            <div class="stat-label">Pending MoMo Verifications</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-mobile-alt"></i></div>
        <div>
            <div class="stat-value">RWF {{ number_format($stats['total_momo']) }}</div>
            <div class="stat-label">Mobile Money Collected</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_cod'] }}</div>
            <div class="stat-label">Cash on Delivery Orders</div>
        </div>
    </div>
</div>

{{-- FILTERS --}}
<div class="filter-bar">
    <span style="font-size:13px;font-weight:600;color:var(--gray)">Method:</span>
    <a href="{{ route('admin.payments') }}?method=all&status={{ request('status','all') }}" class="filter-tab {{ request('method','all') === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('admin.payments') }}?method=mobile_money&status={{ request('status','all') }}" class="filter-tab {{ request('method') === 'mobile_money' ? 'active' : '' }}"><i class="fas fa-mobile-alt"></i> Mobile Money</a>
    <a href="{{ route('admin.payments') }}?method=cash_on_delivery&status={{ request('status','all') }}" class="filter-tab {{ request('method') === 'cash_on_delivery' ? 'active' : '' }}"><i class="fas fa-money-bill"></i> Cash on Delivery</a>

    <span style="font-size:13px;font-weight:600;color:var(--gray);margin-left:10px">Status:</span>
    <a href="{{ route('admin.payments') }}?method={{ request('method','all') }}&status=all" class="filter-tab {{ request('status','all') === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('admin.payments') }}?method={{ request('method','all') }}&status=pending" class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('admin.payments') }}?method={{ request('method','all') }}&status=paid" class="filter-tab {{ request('status') === 'paid' ? 'active' : '' }}">Paid</a>
    <a href="{{ route('admin.payments') }}?method={{ request('method','all') }}&status=failed" class="filter-tab {{ request('status') === 'failed' ? 'active' : '' }}">Failed</a>
</div>

{{-- PAYMENTS TABLE --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-credit-card" style="color:var(--primary)"></i> All Payments ({{ $payments->total() }})</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>MoMo Phone</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $order)
                <tr>
                    <td>
                        <strong>{{ $order->order_number }}</strong><br>
                        <small style="color:var(--gray)">{{ $order->customer_name }}</small>
                    </td>
                    <td>
                        {{ $order->customer_email }}<br>
                        <small style="color:var(--gray)">{{ $order->customer_phone }}</small>
                    </td>
                    <td>
                        @if($order->payment_method === 'mobile_money')
                            <span class="badge-momo"><i class="fas fa-mobile-alt"></i> MoMo</span>
                        @else
                            <span class="badge-cod"><i class="fas fa-money-bill-wave"></i> COD</span>
                        @endif
                    </td>
                    <td>
                        @if($order->momo_phone)
                            <span class="momo-phone"><i class="fas fa-phone"></i> {{ $order->momo_phone }}</span>
                        @else
                            <span style="color:var(--gray);font-size:12px">—</span>
                        @endif
                    </td>
                    <td><strong style="color:#0f172a">RWF {{ number_format($order->total) }}</strong></td>
                    <td>
                        <span class="badge-{{ $order->payment_status }}">
                            @if($order->payment_status === 'paid') ✅ Paid
                            @elseif($order->payment_status === 'pending') ⏳ Pending
                            @else ❌ Failed
                            @endif
                        </span>
                    </td>
                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="color:var(--gray);font-size:12px">
                        {{ $order->created_at->format('d M Y') }}<br>
                        {{ $order->created_at->format('H:i') }}
                    </td>
                    <td>
                        <form action="{{ route('admin.payments.status', $order) }}" method="POST" style="display:flex;gap:6px;align-items:center">
                            @csrf @method('PATCH')
                            <select name="payment_status" class="form-control" style="padding:5px 8px;font-size:12px;width:auto;min-width:100px">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="paid"    {{ $order->payment_status === 'paid'    ? 'selected' : '' }}>✅ Paid</option>
                                <option value="failed"  {{ $order->payment_status === 'failed'  ? 'selected' : '' }}>❌ Failed</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;color:var(--gray);padding:40px">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $payments->links() }}</div>
</div>
@endsection
