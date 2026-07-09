@extends('customer.layout')
@section('title', 'My Orders')

@push('styles')
<style>
    .filter-tabs{display:flex;gap:6px;margin-bottom:20px;flex-wrap:wrap}
    .filter-tab{padding:7px 16px;border-radius:20px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid var(--border);background:#fff;color:var(--gray);transition:all .2s}
    .filter-tab.active,.filter-tab:hover{background:var(--primary);color:#fff;border-color:var(--primary)}
    .order-card{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:box-shadow .2s}
    .order-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.08)}
    .order-card-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:#f8fafc;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:8px}
    .order-card-body{padding:16px 18px}
    .order-items-preview{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
    .order-item-thumb{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid var(--border)}
    .order-item-thumb-placeholder{width:52px;height:52px;border-radius:8px;border:1px solid var(--border);background:var(--light);display:flex;align-items:center;justify-content:center;color:var(--gray);font-size:18px}
    .order-card-footer{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border);flex-wrap:wrap;gap:8px}
    .track-box{background:linear-gradient(135deg,#fffbf0,#fff7ed);border:1px solid #fde68a;border-radius:12px;padding:20px}
    .track-box h3{font-size:15px;font-weight:700;margin-bottom:4px;color:var(--dark)}
    .track-box p{font-size:13px;color:var(--gray);margin-bottom:14px}
    .track-input-row{display:flex;gap:0}
    .track-input-row input{flex:1;padding:10px 14px;border:1px solid var(--border);border-right:none;border-radius:8px 0 0 8px;font-size:13.5px;outline:none}
    .track-input-row input:focus{border-color:var(--primary)}
    .track-input-row button{padding:10px 20px;background:var(--primary);color:#fff;border:none;border-radius:0 8px 8px 0;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap}
    .track-input-row button:hover{background:var(--primary-dark)}
</style>
@endpush

@section('content')

{{-- TRACK ORDER BOX --}}
<div class="track-box" id="track">
    <h3><i class="fas fa-map-marker-alt" style="color:var(--primary)"></i> Track Your Order</h3>
    <p>Enter your order number to see the current status and delivery progress.</p>
    <form action="{{ route('customer.orders.search') }}" method="POST">
        @csrf
        <div class="track-input-row">
            <input type="text" name="order_number" placeholder="e.g. PS-ABCD1234" value="{{ old('order_number') }}" required>
            <button type="submit"><i class="fas fa-search"></i> Track</button>
        </div>
    </form>
</div>

{{-- FILTER TABS --}}
<div class="filter-tabs">
    @php $statuses = ['all'=>'All Orders','pending'=>'Pending','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled']; @endphp
    @foreach($statuses as $val => $label)
        <a href="{{ route('customer.orders') }}?status={{ $val }}" class="filter-tab {{ request('status',$val==='all'?'all':'') === $val || (!request('status') && $val==='all') ? 'active' : '' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- ORDERS LIST --}}
<div style="display:flex;flex-direction:column;gap:14px">
    @forelse($orders as $order)
    <div class="order-card">
        <div class="order-card-header">
            <div>
                <div style="font-size:14px;font-weight:700">{{ $order->order_number }}</div>
                <div style="font-size:12px;color:var(--gray)">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                <span style="font-size:14px;font-weight:700;color:var(--primary)">RWF {{ number_format($order->total) }}</span>
            </div>
        </div>
        <div class="order-card-body">
            <div class="order-items-preview">
                @foreach($order->items->take(4) as $item)
                    @if($item->product && $item->product->image)
                        <img src="{{ $item->product->image }}" class="order-item-thumb" alt="{{ $item->product_name }}" title="{{ $item->product_name }}" onerror="this.style.display='none'">
                    @else
                        <div class="order-item-thumb-placeholder" title="{{ $item->product_name }}"><i class="fas fa-tshirt"></i></div>
                    @endif
                @endforeach
                @if($order->items->count() > 4)
                    <div class="order-item-thumb-placeholder" style="font-size:13px;font-weight:700">+{{ $order->items->count() - 4 }}</div>
                @endif
            </div>
            <div style="font-size:13px;color:var(--gray)">
                {{ $order->items->count() }} item(s) &nbsp;·&nbsp;
                {{ ucwords(str_replace('_',' ',$order->payment_method)) }} &nbsp;·&nbsp;
                {{ $order->city }}
            </div>
        </div>
        <div class="order-card-footer">
            <div style="font-size:12px;color:var(--gray)">
                <i class="fas fa-map-marker-alt"></i> {{ $order->shipping_address }}
            </div>
            <div style="display:flex;gap:8px">
                <a href="{{ route('customer.track', $order->order_number) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-map-marker-alt"></i> Track Order
                </a>
                <a href="{{ route('order.confirmation', $order->order_number) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye"></i> Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:60px 20px;background:#fff;border:1px solid var(--border);border-radius:14px;color:var(--gray)">
        <i class="fas fa-shopping-bag" style="font-size:48px;opacity:.2;display:block;margin-bottom:16px"></i>
        <p style="font-size:15px;font-weight:600;margin-bottom:8px">No orders found</p>
        <a href="{{ route('shop') }}" class="btn btn-primary"><i class="fas fa-tshirt"></i> Start Shopping</a>
    </div>
    @endforelse
</div>

@if($orders->hasPages())
    <div style="margin-top:20px;display:flex;justify-content:center">{{ $orders->links() }}</div>
@endif

@endsection
