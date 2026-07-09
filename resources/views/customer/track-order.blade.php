@extends('customer.layout')
@section('title', 'Track Order – ' . $order->order_number)

@push('styles')
<style>
    .track-hero{background:linear-gradient(135deg,var(--dark),#16213e);border-radius:14px;padding:24px 28px;color:#fff;margin-bottom:4px}
    .track-hero h2{font-size:20px;font-weight:800;margin-bottom:4px}
    .track-hero p{font-size:13px;color:rgba(255,255,255,.6)}
    .order-num{display:inline-block;background:rgba(245,166,35,.2);color:var(--primary);font-size:15px;font-weight:700;padding:6px 16px;border-radius:8px;margin-top:10px}

    /* TIMELINE */
    .timeline{display:flex;align-items:flex-start;justify-content:space-between;position:relative;padding:30px 0;margin-bottom:4px}
    .timeline::before{content:'';position:absolute;top:44px;left:10%;right:10%;height:3px;background:var(--border);z-index:0}
    .timeline-progress{position:absolute;top:44px;left:10%;height:3px;background:var(--primary);z-index:1;transition:width .5s ease}
    .tl-step{display:flex;flex-direction:column;align-items:center;gap:10px;flex:1;position:relative;z-index:2}
    .tl-dot{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;border:3px solid var(--border);background:#fff;transition:all .3s}
    .tl-dot.done{background:var(--primary);border-color:var(--primary);color:#fff}
    .tl-dot.current{background:var(--primary);border-color:var(--primary);color:#fff;box-shadow:0 0 0 6px rgba(245,166,35,.2)}
    .tl-dot.cancelled{background:#ef4444;border-color:#ef4444;color:#fff}
    .tl-label{font-size:12px;font-weight:600;color:var(--gray);text-align:center}
    .tl-label.done,.tl-label.current{color:var(--dark)}
    .tl-date{font-size:10px;color:var(--gray);text-align:center}

    /* DETAIL GRID */
    .detail-grid{display:grid;grid-template-columns:1fr 340px;gap:20px}
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .info-item label{font-size:11px;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:3px}
    .info-item span{font-size:13.5px;font-weight:600;color:var(--dark)}
    .order-item-row{display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border)}
    .order-item-row:last-child{border-bottom:none}
    .item-img{width:54px;height:54px;object-fit:cover;border-radius:8px;border:1px solid var(--border);flex-shrink:0}
    .item-img-ph{width:54px;height:54px;border-radius:8px;border:1px solid var(--border);background:var(--light);display:flex;align-items:center;justify-content:center;color:var(--gray);flex-shrink:0}
    .summary-row{display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid var(--border)}
    .summary-row:last-child{border-bottom:none;font-size:16px;font-weight:800;padding-top:12px}

    @media(max-width:900px){.detail-grid{grid-template-columns:1fr}.info-grid{grid-template-columns:1fr}}
    @media(max-width:600px){.timeline{flex-direction:column;gap:0}.timeline::before{display:none}.timeline-progress{display:none}.tl-step{flex-direction:row;justify-content:flex-start;padding:10px 0;border-bottom:1px solid var(--border)}.tl-step:last-child{border-bottom:none}.tl-label{text-align:left}}
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="track-hero">
    <h2><i class="fas fa-map-marker-alt" style="color:var(--primary)"></i> Order Tracking</h2>
    <p>Real-time status of your order</p>
    <div class="order-num">{{ $order->order_number }}</div>
    <div style="margin-top:10px;font-size:13px;color:rgba(255,255,255,.6)">
        Placed on {{ $order->created_at->format('d M Y, H:i') }}
    </div>
</div>

{{-- TIMELINE --}}
@php
    $steps = [
        'pending'    => ['label' => 'Order Placed',  'icon' => 'fas fa-check'],
        'processing' => ['label' => 'Processing',    'icon' => 'fas fa-cog'],
        'shipped'    => ['label' => 'Shipped',        'icon' => 'fas fa-truck'],
        'delivered'  => ['label' => 'Delivered',      'icon' => 'fas fa-home'],
    ];
    $stepKeys    = array_keys($steps);
    $currentIdx  = array_search($order->status, $stepKeys);
    $isCancelled = $order->status === 'cancelled';
    $progressPct = $isCancelled ? 0 : ($currentIdx !== false ? ($currentIdx / (count($steps) - 1)) * 80 : 0);
@endphp

<div class="card">
    @if($isCancelled)
        <div style="text-align:center;padding:20px;background:#fef2f2;border-radius:10px;color:#991b1b;font-weight:700;font-size:15px">
            <i class="fas fa-times-circle" style="font-size:28px;display:block;margin-bottom:8px"></i>
            This order has been cancelled.
        </div>
    @else
    <div class="timeline">
        <div class="timeline-progress" style="width:{{ $progressPct }}%"></div>
        @foreach($steps as $key => $step)
        @php
            $idx     = array_search($key, $stepKeys);
            $isDone  = $currentIdx !== false && $idx < $currentIdx;
            $isCurr  = $order->status === $key;
        @endphp
        <div class="tl-step">
            <div class="tl-dot {{ $isDone ? 'done' : ($isCurr ? 'current' : '') }}">
                <i class="{{ $step['icon'] }}"></i>
            </div>
            <div class="tl-label {{ $isDone || $isCurr ? 'done' : '' }}">{{ $step['label'] }}</div>
            <div class="tl-date">{{ $isDone || $isCurr ? $order->updated_at->format('d M') : '—' }}</div>
        </div>
        @endforeach
    </div>

    {{-- STATUS MESSAGE --}}
    @php
        $messages = [
            'pending'    => ['color'=>'#92400e','bg'=>'#fef3c7','icon'=>'fas fa-clock',       'msg'=>'Your order has been received and is awaiting processing.'],
            'processing' => ['color'=>'#1e40af','bg'=>'#dbeafe','icon'=>'fas fa-cog',         'msg'=>'We are preparing your items for shipment.'],
            'shipped'    => ['color'=>'#3730a3','bg'=>'#e0e7ff','icon'=>'fas fa-truck',       'msg'=>'Your order is on its way! Expected delivery in 2–4 days.'],
            'delivered'  => ['color'=>'#065f46','bg'=>'#d1fae5','icon'=>'fas fa-check-circle','msg'=>'Your order has been delivered. Enjoy your purchase!'],
        ];
        $msg = $messages[$order->status] ?? null;
    @endphp
    @if($msg)
    <div style="display:flex;align-items:center;gap:12px;padding:14px 18px;background:{{ $msg['bg'] }};border-radius:10px;color:{{ $msg['color'] }};font-size:13.5px;font-weight:600;margin-top:4px">
        <i class="{{ $msg['icon'] }}" style="font-size:18px;flex-shrink:0"></i>
        {{ $msg['msg'] }}
    </div>
    @endif
    @endif
</div>

{{-- ORDER DETAILS --}}
<div class="detail-grid">
    <div>
        {{-- DELIVERY INFO --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h2><i class="fas fa-map-marker-alt"></i> Delivery Information</h2></div>
            <div class="info-grid">
                <div class="info-item"><label>Recipient</label><span>{{ $order->customer_name }}</span></div>
                <div class="info-item"><label>Phone</label><span>{{ $order->customer_phone }}</span></div>
                <div class="info-item"><label>Email</label><span>{{ $order->customer_email }}</span></div>
                <div class="info-item"><label>City</label><span>{{ $order->city }}</span></div>
                <div class="info-item" style="grid-column:1/-1"><label>Address</label><span>{{ $order->shipping_address }}</span></div>
                <div class="info-item"><label>Payment</label><span>{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</span></div>
                <div class="info-item"><label>Status</label><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></div>
            </div>
        </div>

        {{-- ITEMS --}}
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-box"></i> Items Ordered</h2></div>
            @foreach($order->items as $item)
            <div class="order-item-row">
                @if($item->product && $item->product->image)
                    <img src="{{ $item->product->image }}" class="item-img" alt="{{ $item->product_name }}" onerror="this.style.display='none'">
                @else
                    <div class="item-img-ph"><i class="fas fa-tshirt"></i></div>
                @endif
                <div style="flex:1">
                    <div style="font-size:13.5px;font-weight:600;margin-bottom:2px">{{ $item->product_name }}</div>
                    <div style="font-size:12px;color:var(--gray)">Qty: {{ $item->quantity }} × RWF {{ number_format($item->price) }}</div>
                </div>
                <div style="font-size:14px;font-weight:700;color:var(--primary)">RWF {{ number_format($item->subtotal) }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ORDER SUMMARY --}}
    <div>
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-receipt"></i> Order Summary</h2></div>
            <div class="summary-row"><span>Subtotal</span><span>RWF {{ number_format($order->subtotal) }}</span></div>
            <div class="summary-row"><span>Shipping</span><span>RWF {{ number_format($order->shipping) }}</span></div>
            <div class="summary-row"><span>Total</span><span style="color:var(--primary)">RWF {{ number_format($order->total) }}</span></div>

            <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('customer.orders') }}" class="btn btn-secondary" style="justify-content:center"><i class="fas fa-arrow-left"></i> Back to Orders</a>
                <a href="{{ route('shop') }}" class="btn btn-primary" style="justify-content:center"><i class="fas fa-tshirt"></i> Continue Shopping</a>
            </div>
        </div>

        {{-- NEED HELP --}}
        <div class="card" style="margin-top:16px;background:linear-gradient(135deg,#fffbf0,#fff7ed);border-color:#fde68a">
            <h3 style="font-size:14px;font-weight:700;margin-bottom:8px"><i class="fas fa-headset" style="color:var(--primary)"></i> Need Help?</h3>
            <p style="font-size:12px;color:var(--gray);margin-bottom:12px">If you have any questions about your order, contact us.</p>
            <a href="tel:+250791591773" class="btn btn-primary btn-sm" style="width:100%;justify-content:center"><i class="fas fa-phone"></i> +250 795 9151</a>
            <a href="mailto:davidfnatt2002@gmail.com" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;margin-top:6px"><i class="fas fa-envelope"></i> davidfnatt2002@gmail.com</a>
        </div>
    </div>
</div>

@endsection
