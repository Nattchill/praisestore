@extends('admin.layout')
@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Detail')
@section('breadcrumb', 'Orders / ' . $order->order_number)

@push('styles')
<style>
    .detail-grid{display:grid;grid-template-columns:1fr 340px;gap:20px}
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px}
    .info-item label{font-size:11px;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:4px}
    .info-item span{font-size:14px;font-weight:600;color:var(--dark)}
    .order-item-row{display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border)}
    .order-item-row:last-child{border-bottom:none}
    .item-img{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid var(--border);flex-shrink:0}
    .item-info{flex:1}
    .item-info h4{font-size:13px;font-weight:600;margin-bottom:2px}
    .item-info span{font-size:12px;color:var(--gray)}
    .item-price{font-size:14px;font-weight:700;color:var(--primary)}
    .summary-row{display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid var(--border)}
    .summary-row:last-child{border-bottom:none;font-size:16px;font-weight:800;padding-top:12px}
    .timeline{list-style:none;padding:0}
    .timeline li{display:flex;align-items:flex-start;gap:12px;padding:10px 0;position:relative}
    .timeline li:not(:last-child)::after{content:'';position:absolute;left:11px;top:32px;bottom:0;width:2px;background:var(--border)}
    .tl-dot{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;z-index:1}
    .tl-dot.done{background:#d1fae5;color:#065f46}
    .tl-dot.current{background:var(--primary);color:#fff}
    .tl-dot.pending{background:var(--light);color:var(--gray);border:1px solid var(--border)}
    .tl-info h4{font-size:13px;font-weight:600;margin-bottom:2px}
    .tl-info span{font-size:11px;color:var(--gray)}
    @media(max-width:900px){.detail-grid{grid-template-columns:1fr}.info-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <a href="{{ route('admin.orders') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Orders</a>
    <span class="badge badge-{{ $order->status }}" style="font-size:13px;padding:6px 14px">{{ ucfirst($order->status) }}</span>
</div>

<div class="detail-grid">
    <div>
        {{-- ORDER INFO --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header">
                <h2><i class="fas fa-info-circle" style="color:var(--primary)"></i> Order Information</h2>
                <strong style="font-size:15px">{{ $order->order_number }}</strong>
            </div>
            <div class="info-grid">
                <div class="info-item"><label>Customer Name</label><span>{{ $order->customer_name }}</span></div>
                <div class="info-item"><label>Email</label><span>{{ $order->customer_email }}</span></div>
                <div class="info-item"><label>Phone</label><span>{{ $order->customer_phone }}</span></div>
                <div class="info-item"><label>City</label><span>{{ $order->city }}</span></div>
                <div class="info-item"><label>Shipping Address</label><span>{{ $order->shipping_address }}</span></div>
                <div class="info-item"><label>Payment Method</label><span>{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</span></div>
                <div class="info-item"><label>Order Date</label><span>{{ $order->created_at->format('d M Y, H:i') }}</span></div>
                <div class="info-item"><label>Last Updated</label><span>{{ $order->updated_at->format('d M Y, H:i') }}</span></div>
            </div>
        </div>

        {{-- ORDER ITEMS --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-box" style="color:var(--primary)"></i> Items Ordered</h2>
                <span style="font-size:13px;color:var(--gray)">{{ $order->items->count() }} item(s)</span>
            </div>
            @foreach($order->items as $item)
            <div class="order-item-row">
                @if($item->product && $item->product->image)
                    <img src="{{ $item->product->image }}" class="item-img" alt="{{ $item->product_name }}">
                @else
                    <div class="item-img" style="background:var(--light);display:flex;align-items:center;justify-content:center;color:var(--gray)"><i class="fas fa-tshirt"></i></div>
                @endif
                <div class="item-info">
                    <h4>{{ $item->product_name }}</h4>
                    <span>Qty: {{ $item->quantity }} × RWF {{ number_format($item->price) }}</span>
                </div>
                <div class="item-price">RWF {{ number_format($item->subtotal) }}</div>
            </div>
            @endforeach
            <div style="margin-top:16px;padding-top:16px;border-top:2px solid var(--border)">
                <div class="summary-row"><span>Subtotal</span><span>RWF {{ number_format($order->subtotal) }}</span></div>
                <div class="summary-row"><span>Shipping</span><span>RWF {{ number_format($order->shipping) }}</span></div>
                <div class="summary-row"><span>Total</span><span style="color:var(--primary)">RWF {{ number_format($order->total) }}</span></div>
            </div>
        </div>
    </div>

    <div>
        {{-- UPDATE STATUS --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h2><i class="fas fa-edit" style="color:var(--primary)"></i> Update Status</h2></div>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf @method('PATCH')
                <div class="form-group">
                    <label>Order Status</label>
                    <select name="status" class="form-control">
                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-save"></i> Update Status</button>
            </form>
        </div>

        {{-- ORDER TIMELINE --}}
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-history" style="color:var(--primary)"></i> Order Timeline</h2></div>
            @php
                $steps = ['pending' => 'Order Placed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
                $statusOrder = array_keys($steps);
                $currentIndex = array_search($order->status, $statusOrder);
            @endphp
            <ul class="timeline">
                @foreach($steps as $key => $label)
                @php
                    $stepIndex = array_search($key, $statusOrder);
                    $isDone = $currentIndex !== false && $stepIndex < $currentIndex;
                    $isCurrent = $order->status === $key;
                @endphp
                <li>
                    <div class="tl-dot {{ $isDone ? 'done' : ($isCurrent ? 'current' : 'pending') }}">
                        <i class="fas fa-{{ $isDone ? 'check' : ($isCurrent ? 'circle' : 'circle') }}"></i>
                    </div>
                    <div class="tl-info">
                        <h4>{{ $label }}</h4>
                        <span>{{ $isCurrent ? 'Current status' : ($isDone ? 'Completed' : 'Pending') }}</span>
                    </div>
                </li>
                @endforeach
                @if($order->status === 'cancelled')
                <li>
                    <div class="tl-dot" style="background:#fee2e2;color:#991b1b"><i class="fas fa-times"></i></div>
                    <div class="tl-info"><h4>Cancelled</h4><span>Order was cancelled</span></div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
