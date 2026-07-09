@extends('admin.layout')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('breadcrumb', 'Reports')

@push('styles')
<style>
    .period-tabs{display:flex;gap:6px;margin-bottom:20px}
    .period-tab{padding:7px 18px;border-radius:20px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid var(--border);background:#fff;color:var(--gray);transition:all .2s}
    .period-tab.active{background:var(--primary);color:#fff;border-color:var(--primary)}
    .summary-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
    .summary-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px}
    .summary-card .val{font-size:22px;font-weight:800;color:var(--dark);margin-bottom:4px}
    .summary-card .lbl{font-size:12px;color:var(--gray)}
    .summary-card .icon{font-size:22px;margin-bottom:10px}
    .charts-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px}
    .chart-wrap{height:280px;position:relative}
    .donut-wrap{height:280px;position:relative}
    .tables-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
    @media(max-width:1100px){.charts-grid{grid-template-columns:1fr}.tables-grid{grid-template-columns:1fr}}
    @media(max-width:700px){.summary-grid{grid-template-columns:repeat(2,1fr)}}
</style>
@endpush

@section('content')

{{-- PERIOD FILTER --}}
<div class="period-tabs">
    @foreach([7=>'Last 7 Days', 30=>'Last 30 Days', 90=>'Last 90 Days', 365=>'Last Year'] as $days => $label)
    <a href="{{ route('admin.reports') }}?period={{ $days }}" class="period-tab {{ $period == $days ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

{{-- SUMMARY CARDS --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="icon" style="color:#22c55e">💰</div>
        <div class="val">RWF {{ number_format($summary['total_revenue']) }}</div>
        <div class="lbl">Total Revenue</div>
    </div>
    <div class="summary-card">
        <div class="icon" style="color:#3b82f6">🛒</div>
        <div class="val">{{ $summary['total_orders'] }}</div>
        <div class="lbl">Total Orders</div>
    </div>
    <div class="summary-card">
        <div class="icon" style="color:var(--primary)">📊</div>
        <div class="val">RWF {{ number_format($summary['avg_order']) }}</div>
        <div class="lbl">Avg. Order Value</div>
    </div>
    <div class="summary-card">
        <div class="icon" style="color:#8b5cf6">👥</div>
        <div class="val">{{ $summary['new_customers'] }}</div>
        <div class="lbl">New Customers</div>
    </div>
</div>

{{-- CHARTS --}}
<div class="charts-grid">
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-chart-area" style="color:var(--primary)"></i> Daily Sales</h2></div>
        <div class="chart-wrap"><canvas id="salesChart"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-chart-pie" style="color:var(--primary)"></i> Orders by Status</h2></div>
        <div class="donut-wrap"><canvas id="statusChart"></canvas></div>
    </div>
</div>

{{-- TABLES --}}
<div class="tables-grid">
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-tags" style="color:var(--primary)"></i> Revenue by Category</h2></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Category</th><th>Units Sold</th><th>Revenue</th></tr></thead>
                <tbody>
                    @forelse($topCategories as $cat)
                    <tr>
                        <td><strong>{{ $cat->name }}</strong></td>
                        <td>{{ $cat->total_sold }}</td>
                        <td><strong style="color:var(--primary)">RWF {{ number_format($cat->revenue) }}</strong></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--gray);padding:20px">No data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2><i class="fas fa-trophy" style="color:var(--primary)"></i> Top Products</h2></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Product</th><th>Sold</th><th>Revenue</th></tr></thead>
                <tbody>
                    @forelse($topProducts as $i => $p)
                    <tr>
                        <td>
                            <span style="width:22px;height:22px;border-radius:50%;background:{{ $i===0?'#fef3c7':($i===1?'#f3f4f6':($i===2?'#fef3c7':'#f9fafb')) }};display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">{{ $i+1 }}</span>
                        </td>
                        <td style="font-size:12px">{{ Str::limit($p->product_name, 25) }}</td>
                        <td>{{ $p->total_sold }}</td>
                        <td style="font-size:12px;font-weight:700;color:var(--primary)">RWF {{ number_format($p->revenue) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--gray);padding:20px">No data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Sales chart
const salesData = @json($salesData);
new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: salesData.map(d => d.date),
        datasets: [{
            label: 'Revenue',
            data: salesData.map(d => d.revenue),
            backgroundColor: 'rgba(245,166,35,.7)',
            borderColor: '#f5a623',
            borderWidth: 1,
            borderRadius: 4,
        }, {
            label: 'Orders',
            data: salesData.map(d => d.orders),
            backgroundColor: 'rgba(59,130,246,.5)',
            borderColor: '#3b82f6',
            borderWidth: 1,
            borderRadius: 4,
            yAxisID: 'y1',
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'RWF ' + (v/1000).toFixed(0) + 'k' } },
            y1: { beginAtZero: true, position: 'right', grid: { display: false } },
            x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
        }
    }
});

// Status donut chart
const statusData = @json($ordersByStatus);
const statusLabels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
const statusValues = Object.values(statusData);
const statusColors = { pending:'#fbbf24', processing:'#60a5fa', shipped:'#818cf8', delivered:'#34d399', cancelled:'#f87171' };
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{ data: statusValues, backgroundColor: Object.keys(statusData).map(s => statusColors[s] || '#e5e7eb'), borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 12 } } } },
        cutout: '65%'
    }
});
</script>
@endpush
