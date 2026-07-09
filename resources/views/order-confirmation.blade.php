@extends('layouts.store')
@section('title', 'Order Confirmed – PraiseStore')

@push('styles')
<style>
    .confirmation-wrap{max-width:700px;margin:50px auto;padding:0 20px 60px}
    .success-header{text-align:center;padding:40px 20px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);border-radius:12px;margin-bottom:30px}
    .success-icon{width:80px;height:80px;background:#10b981;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:36px;color:#fff}
    .success-header h1{font-size:26px;font-weight:800;color:#065f46;margin-bottom:8px}
    .success-header p{color:#047857;font-size:15px}
    .order-number{display:inline-block;background:#fff;border:2px solid #10b981;color:#065f46;font-size:18px;font-weight:700;padding:8px 20px;border-radius:8px;margin-top:12px}
    .order-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px;margin-bottom:20px}
    .order-card h3{font-size:16px;font-weight:700;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border);color:var(--dark)}
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .info-item label{font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:4px}
    .info-item span{font-size:14px;font-weight:600;color:var(--dark)}
    .order-item{display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border)}
    .order-item:last-child{border-bottom:none}
    .order-item img{width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid var(--border)}
    .order-item-info{flex:1}
    .order-item-info h4{font-size:14px;font-weight:600;margin-bottom:2px}
    .order-item-info span{font-size:12px;color:var(--gray)}
    .order-item-price{font-size:15px;font-weight:700;color:var(--primary)}
    .totals-row{display:flex;justify-content:space-between;padding:8px 0;font-size:14px}
    .totals-row.grand{font-size:18px;font-weight:800;border-top:2px solid var(--dark);padding-top:12px;margin-top:4px}
    .status-badge{display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;background:#fef3c7;color:#92400e}
    .actions{display:flex;gap:12px;justify-content:center;margin-top:24px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:6px;font-weight:600;font-size:14px;cursor:pointer;border:none;transition:all .2s}
    .btn-primary{background:var(--primary);color:#fff}
    .btn-primary:hover{background:#e09415}
    .btn-outline{background:transparent;color:var(--dark);border:2px solid var(--border)}
    .btn-outline:hover{border-color:var(--dark)}
</style>
@endpush

@section('content')
<div class="confirmation-wrap">
    <div class="success-header">
        <div class="success-icon"><i class="fas fa-check"></i></div>
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for shopping with PraiseStore. Your order has been received.</p>
        <div class="order-number">{{ $order->order_number }}</div>
    </div>

    <div class="order-card">
        <h3><i class="fas fa-info-circle" style="color:var(--primary)"></i> Order Details</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Customer Name</label>
                <span>{{ $order->customer_name }}</span>
            </div>
            <div class="info-item">
                <label>Email</label>
                <span>{{ $order->customer_email }}</span>
            </div>
            <div class="info-item">
                <label>Phone</label>
                <span>{{ $order->customer_phone }}</span>
            </div>
            <div class="info-item">
                <label>City</label>
                <span>{{ $order->city }}</span>
            </div>
            <div class="info-item">
                <label>Shipping Address</label>
                <span>{{ $order->shipping_address }}</span>
            </div>
            <div class="info-item">
                <label>Payment Method</label>
                <span>{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            <div class="info-item">
                <label>Order Status</label>
                <span class="status-badge">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="info-item">
                <label>Order Date</label>
                <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>

    <div class="order-card">
        <h3><i class="fas fa-box" style="color:var(--primary)"></i> Items Ordered</h3>
        @foreach($order->items as $item)
        <div class="order-item">
            <div class="order-item-info">
                <h4>{{ $item->product_name }}</h4>
                <span>Qty: {{ $item->quantity }} × RWF {{ number_format($item->price) }}</span>
            </div>
            <div class="order-item-price">RWF {{ number_format($item->subtotal) }}</div>
        </div>
        @endforeach
        <div class="totals-row"><span>Subtotal</span><span>RWF {{ number_format($order->subtotal) }}</span></div>
        <div class="totals-row"><span>Shipping</span><span>RWF {{ number_format($order->shipping) }}</span></div>
        <div class="totals-row grand"><span>Total</span><span>RWF {{ number_format($order->total) }}</span></div>
    </div>

    <div class="actions">
        <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-home"></i> Back to Home</a>
        <a href="{{ route('shop') }}" class="btn btn-outline"><i class="fas fa-shopping-bag"></i> Continue Shopping</a>
    </div>
</div>
@endsection
