@extends('layouts.store')
@section('title', 'Shopping Cart – PraiseStore')

@push('styles')
<style>
    .page-banner{background:var(--ig-gradient);color:#fff;padding:30px 0;margin-bottom:30px}
    .page-banner h1{font-size:28px;font-weight:700}
    .page-banner .breadcrumb{font-size:13px;color:rgba(255,255,255,.6)}
    .page-banner .breadcrumb a{color:#ffd6e7}
    .cart-table{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .cart-table th{background:var(--light);padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:var(--gray);text-transform:uppercase;letter-spacing:.5px}
    .qty-input button:hover{background:var(--ig-gradient);color:#fff}
    .cart-summary{background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px;height:fit-content}
    .cart-summary h3{font-size:18px;font-weight:700;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid var(--border);background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .summary-row.total{font-size:18px;font-weight:700;color:var(--dark);border-top:2px solid var(--border);padding-top:14px;margin-top:14px}
    .btn-outline{background:transparent;color:var(--dark);border:2px solid var(--border)}
    .btn-outline:hover{border-color:var(--primary);color:var(--primary)}
    .empty-cart{text-align:center;padding:80px 20px;color:var(--gray)}
    .empty-cart i{font-size:64px;margin-bottom:20px;opacity:.2}
    @media(max-width:900px){.cart-layout{grid-template-columns:1fr}}
    @media(max-width:600px){.cart-table th:nth-child(3),.cart-table td:nth-child(3){display:none}}
</style>
@endpush

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Cart</div>
    </div>
</div>

<div class="container">
    @if(empty($items))
        <div class="empty-cart">
            <i class="fas fa-shopping-bag"></i>
            <h2>Your cart is empty</h2>
            <p style="margin:10px 0 24px">Looks like you haven't added anything yet.</p>
            <a href="{{ route('shop') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--primary);color:#fff;border-radius:6px;font-weight:600">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>
    @else
    <div class="cart-layout">
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $id => $item)
                    <tr>
                        <td>
                            <div class="cart-product">
                                <img src="{{ $item['image'] ?? 'https://via.placeholder.com/70' }}" alt="{{ $item['name'] }}">
                                <div>
                                    <h4>{{ $item['name'] }}</h4>
                                </div>
                            </div>
                        </td>
                        <td>RWF {{ number_format($item['price']) }}</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" style="display:flex;align-items:center;gap:8px">
                                @csrf @method('PATCH')
                                <div class="qty-input">
                                    <button type="button" onclick="this.nextElementSibling.stepDown();this.closest('form').submit()">−</button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" onchange="this.closest('form').submit()">
                                    <button type="button" onclick="this.previousElementSibling.stepUp();this.closest('form').submit()">+</button>
                                </div>
                            </form>
                        </td>
                        <td><strong>RWF {{ number_format($item['price'] * $item['quantity']) }}</strong></td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="remove-btn" title="Remove"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <div class="summary-row"><span>Subtotal</span><span>RWF {{ number_format($total) }}</span></div>
            <div class="summary-row"><span>Shipping</span><span>RWF 2,000</span></div>
            <div class="summary-row total"><span>Total</span><span>RWF {{ number_format($total + 2000) }}</span></div>
            <a href="{{ route('checkout') }}" class="btn btn-primary"><i class="fas fa-lock"></i> Proceed to Checkout</a>
            <a href="{{ route('shop') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
        </div>
    </div>
    @endif
</div>
@endsection
