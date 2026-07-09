@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Overview')

@push('styles')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:16px;margin-bottom:24px}
    .stat-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px;display:flex;flex-direction:column;gap:10px}
    .stat-top{display:flex;align-items:center;justify-content:space-between}
    .stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px}
    .stat-icon.orange{background:#eff6ff;color:#2563eb}
    .stat-icon.blue{background:#dbeafe;color:#1d4ed8}
    .stat-icon.green{background:#f0fdf4;color:#22c55e}
    .stat-icon.red{background:#fef2f2;color:#ef4444}
    .stat-icon.purple{background:#f5f3ff;color:#8b5cf6}
    .stat-icon.teal{background:#f0fdfa;color:#14b8a6}
    .stat-value{font-size:22px;font-weight:800;color:var(--dark)}
    .stat-label{font-size:12px;color:var(--gray);font-weight:500}
    .stat-change{font-size:11px;font-weight:600;display:flex;align-items:center;gap:3px}
    .stat-change.up{color:#22c55e} .stat-change.down{color:#ef4444}

    .grid-2{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px}
    .grid-2-equal{display:grid;grid-template-columns:1fr 1fr;gap:20px}

    .chart-wrap{position:relative;height:260px}
    canvas{max-height:260px}

    .top-product{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)}
    .top-product:last-child{border-bottom:none}
    .top-product-rank{width:24px;height:24px;border-radius:50%;background:var(--light);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--gray);flex-shrink:0}
    .top-product-rank.gold{background:#fef3c7;color:#92400e}
    .top-product-info{flex:1}
    .top-product-info h4{font-size:13px;font-weight:600;margin-bottom:2px}
    .top-product-info span{font-size:11px;color:var(--gray)}
    .top-product-rev{font-size:13px;font-weight:700;color:var(--primary)}

    .order-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)}
    .order-row:last-child{border-bottom:none}
    .order-num{font-size:13px;font-weight:700;color:var(--dark);min-width:110px}
    .order-customer{flex:1;font-size:13px;color:var(--gray)}
    .order-amount{font-size:13px;font-weight:700;color:var(--dark);min-width:100px;text-align:right}

    @media(max-width:1200px){.stats-grid{grid-template-columns:repeat(3,1fr)}}
    @media(max-width:900px){.stats-grid{grid-template-columns:repeat(2,1fr)}.grid-2{grid-template-columns:1fr}.grid-2-equal{grid-template-columns:1fr}}
    @media(max-width:480px){.stats-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

{{-- PENDING MOMO ALERT --}}
@php $pendingMomo = \App\Models\Order::where('payment_method','mobile_money')->where('payment_status','pending')->get(); @endphp
@if($pendingMomo->count() > 0)
<div style="background:#fefce8;border:1.5px solid #fde68a;border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div style="display:flex;align-items:center;gap:12px">
        <div style="width:40px;height:40px;background:#fef08a;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px">⏳</div>
        <div>
            <div style="font-size:14px;font-weight:700;color:#854d0e">{{ $pendingMomo->count() }} Mobile Money Payment{{ $pendingMomo->count() > 1 ? 's' : '' }} Awaiting Verification</div>
            <div style="font-size:12px;color:#92400e">Total: RWF {{ number_format($pendingMomo->sum('total')) }} — Review and confirm these payments</div>
        </div>
    </div>
    <a href="{{ route('admin.payments') }}?method=mobile_money&status=pending" class="btn btn-sm" style="background:#854d0e;color:#fff;white-space:nowrap"><i class="fas fa-credit-card"></i> Review Payments</a>
</div>
@endif

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div><div class="stat-value">RWF {{ number_format($stats['revenue']) }}</div><div class="stat-label">Total Revenue</div></div>
            <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> All time</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div><div class="stat-value">{{ $stats['orders'] }}</div><div class="stat-label">Total Orders</div></div>
            <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
        </div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> All time</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending Orders</div></div>
            <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-change down"><i class="fas fa-exclamation"></i> Needs action</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div><div class="stat-value">{{ $stats['products'] }}</div><div class="stat-label">Products</div></div>
            <div class="stat-icon orange"><i class="fas fa-tshirt"></i></div>
        </div>
        <div class="stat-change up"><i class="fas fa-check"></i> In catalog</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div><div class="stat-value">{{ $stats['customers'] }}</div><div class="stat-label">Customers</div></div>
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> Registered</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div><div class="stat-value">{{ $stats['categories'] }}</div><div class="stat-label">Categories</div></div>
            <div class="stat-icon teal"><i class="fas fa-tags"></i></div>
        </div>
        <div class="stat-change up"><i class="fas fa-check"></i> Active</div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-chart-line" style="color:var(--primary)"></i> Revenue (Last 6 Months)</h2>
        </div>
        <div class="chart-wrap"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-chart-donut" style="color:var(--primary)"></i> Top Products</h2>
            <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm">Full Report</a>
        </div>
        @forelse($topProducts as $i => $p)
        <div class="top-product">
            <div class="top-product-rank {{ $i === 0 ? 'gold' : '' }}">{{ $i + 1 }}</div>
            <div class="top-product-info">
                <h4>{{ $p->product_name }}</h4>
                <span>{{ $p->total_sold }} units sold</span>
            </div>
            <div class="top-product-rev">RWF {{ number_format($p->revenue) }}</div>
        </div>
        @empty
        <p style="color:var(--gray);font-size:13px;text-align:center;padding:20px">No sales data yet.</p>
        @endforelse
    </div>
</div>

{{-- RECENT ORDERS --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-shopping-bag" style="color:var(--primary)"></i> Recent Orders</h2>
        <a href="{{ route('admin.orders') }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-right"></i> View All</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->customer_name }}<br><small style="color:var(--gray)">{{ $order->customer_email }}</small></td>
                    <td>{{ $order->items->count() }}</td>
                    <td><strong>RWF {{ number_format($order->total) }}</strong></td>
                    <td style="font-size:12px">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</td>
                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="color:var(--gray)">{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:var(--gray);padding:30px">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const revenueData = @json($monthlyRevenue);
const labels = revenueData.map(d => months[d.month - 1] + ' ' + d.year);
const values = revenueData.map(d => d.total);

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: labels.length ? labels : ['No data'],
        datasets: [{
            label: 'Revenue (RWF)',
            data: values.length ? values : [0],
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#2563eb',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'RWF ' + (v/1000).toFixed(0) + 'k' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
