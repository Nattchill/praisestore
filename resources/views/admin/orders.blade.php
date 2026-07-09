@extends('admin.layout')
@section('title', 'Orders')
@section('page-title', 'Order Management')
@section('breadcrumb', 'Orders')

@push('styles')
<style>
    .filter-tabs{display:flex;gap:6px;margin-bottom:20px;flex-wrap:wrap}
    .filter-tab{padding:7px 16px;border-radius:20px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid var(--border);background:#fff;color:var(--gray);transition:all .2s}
    .filter-tab:hover,.filter-tab.active{background:var(--primary);color:#fff;border-color:var(--primary)}
</style>
@endpush

@section('content')

{{-- STATUS FILTER TABS --}}
<div class="filter-tabs">
    @php
        $statuses = ['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
        $current = request('status', 'all');
    @endphp
    @foreach($statuses as $val => $label)
    <a href="{{ route('admin.orders') }}?status={{ $val }}" class="filter-tab {{ $current === $val ? 'active' : '' }}">
        {{ $label }}
        @if($val !== 'all')
            <span style="margin-left:4px;opacity:.7">({{ \App\Models\Order::where('status',$val)->count() }})</span>
        @endif
    </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-shopping-bag" style="color:var(--primary)"></i> Orders ({{ $orders->total() }})</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>City</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Pay Status</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>
                        {{ $order->customer_name }}<br>
                        <small style="color:var(--gray)">{{ $order->customer_phone }}</small>
                    </td>
                    <td style="color:var(--gray)">{{ $order->city }}</td>
                    <td>{{ $order->items->count() }}</td>
                    <td><strong>RWF {{ number_format($order->total) }}</strong></td>
                    <td style="font-size:12px">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</td>
                    <td>
                        @if($order->payment_status === 'paid')
                            <span style="background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700">✅ Paid</span>
                        @elseif($order->payment_status === 'failed')
                            <span style="background:#fee2e2;color:#b91c1c;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700">❌ Failed</span>
                        @else
                            <span style="background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700">⏳ Pending</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="color:var(--gray);font-size:12px">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" style="display:flex;gap:4px">
                                @csrf @method('PATCH')
                                <select name="status" class="form-control" style="padding:4px 8px;font-size:12px;width:auto">
                                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                        <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;color:var(--gray);padding:40px">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $orders->links() }}</div>
</div>
@endsection
