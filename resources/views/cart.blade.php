@extends('layouts.store')
@section('title', 'Shopping Cart – PraiseStore')

@push('styles')
<style>
    /* ── PAGE BANNER ── */
    .page-banner{background:var(--ig-gradient);color:#fff;padding:28px 0;margin-bottom:36px}
    .page-banner h1{font-size:26px;font-weight:700;margin-bottom:4px}
    .page-banner .breadcrumb{font-size:13px;color:rgba(255,255,255,.65)}
    .page-banner .breadcrumb a{color:#ffd6e7;text-decoration:underline}

    /* ── LAYOUT ── */
    .cart-layout{display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;padding-bottom:60px}

    /* ── CART TABLE CARD ── */
    .cart-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(193,53,132,.06)}
    .cart-card-header{display:grid;grid-template-columns:minmax(0,2fr) 120px 140px 120px 44px;gap:8px;padding:12px 20px;background:var(--light);border-bottom:1px solid var(--border)}
    .cart-card-header span{font-size:11px;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.6px}
    .cart-card-header span:not(:first-child){text-align:center}

    /* ── CART ROW ── */
    .cart-row{display:grid;grid-template-columns:minmax(0,2fr) 120px 140px 120px 44px;gap:8px;align-items:center;padding:14px 20px;border-bottom:1px solid #fdf0f8;transition:background .15s}
    .cart-row:last-child{border-bottom:none}
    .cart-row:hover{background:#fffafd}

    /* Product cell */
    .cart-product{display:flex;align-items:center;gap:12px;min-width:0}
    .cart-product-img{width:68px;height:68px;min-width:68px;border-radius:10px;object-fit:cover;border:1px solid var(--border);flex-shrink:0;background:#f9f0f5}
    .cart-product-info{min-width:0}
    .cart-product-info h4{font-size:13px;font-weight:600;color:var(--dark);margin-bottom:3px;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .cart-product-info span{font-size:12px;color:var(--gray)}

    /* Price / subtotal cells */
    .cart-cell{text-align:center;font-size:14px;color:var(--dark)}
    .cart-cell.subtotal{font-weight:700;color:var(--primary)}

    /* ── QTY CONTROL ── */
    .qty-wrap{display:flex;align-items:center;justify-content:center}
    .qty-box{display:flex;align-items:center;border:1.5px solid var(--border);border-radius:8px;overflow:hidden;background:#fff}
    .qty-box button{width:30px;height:34px;border:none;background:transparent;cursor:pointer;font-size:15px;color:var(--gray);transition:all .15s;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .qty-box button:hover{background:var(--ig-gradient);color:#fff}
    .qty-box input{width:38px;height:34px;border:none;border-left:1px solid var(--border);border-right:1px solid var(--border);text-align:center;font-size:13px;font-weight:600;color:var(--dark);outline:none;background:#fff;-moz-appearance:textfield}
    .qty-box input::-webkit-outer-spin-button,
    .qty-box input::-webkit-inner-spin-button{-webkit-appearance:none}

    /* ── REMOVE BTN ── */
    .remove-btn{width:32px;height:32px;border:none;background:#fff0f5;border-radius:8px;cursor:pointer;color:#e1306c;font-size:13px;display:flex;align-items:center;justify-content:center;transition:all .2s;margin:0 auto}
    .remove-btn:hover{background:#e1306c;color:#fff;transform:scale(1.1)}

    /* ── CART FOOTER ── */
    .cart-footer{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:var(--light);border-top:1px solid var(--border)}
    .cart-count{font-size:13px;color:var(--gray)}
    .cart-count strong{color:var(--dark)}
    .clear-cart-btn{font-size:12px;color:#e1306c;background:none;border:1px solid #f0d6e8;border-radius:6px;padding:6px 14px;cursor:pointer;transition:all .2s}
    .clear-cart-btn:hover{background:#e1306c;color:#fff;border-color:#e1306c}

    /* ── ORDER SUMMARY ── */
    .cart-summary{background:#fff;border:1px solid var(--border);border-radius:14px;padding:24px;box-shadow:0 2px 12px rgba(193,53,132,.06);position:sticky;top:90px}
    .summary-title{font-size:17px;font-weight:700;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid var(--border);background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .summary-row{display:flex;justify-content:space-between;align-items:center;font-size:14px;color:var(--gray);margin-bottom:12px}
    .summary-row span:last-child{font-weight:600;color:var(--dark)}
    .summary-divider{border:none;border-top:1px dashed var(--border);margin:14px 0}
    .summary-total{display:flex;justify-content:space-between;align-items:center;font-size:18px;font-weight:700;color:var(--dark);margin-bottom:20px}
    .summary-total span:last-child{color:var(--primary)}
    .btn-checkout{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;background:var(--ig-gradient);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;transition:opacity .2s;margin-bottom:10px}
    .btn-checkout:hover{opacity:.9}
    .btn-continue{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;background:#fff;color:var(--dark);border:2px solid var(--border);border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s}
    .btn-continue:hover{border-color:var(--primary);color:var(--primary)}

    /* Payment badges */
    .payment-badges{display:flex;gap:8px;margin-top:16px;padding-top:14px;border-top:1px solid var(--border);justify-content:center;flex-wrap:wrap}
    .pay-badge{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--gray);background:var(--light);border:1px solid var(--border);border-radius:6px;padding:5px 10px}
    .pay-badge i{color:var(--primary)}

    /* ── EMPTY STATE ── */
    .empty-cart{text-align:center;padding:80px 20px;color:var(--gray)}
    .empty-cart i{font-size:72px;margin-bottom:20px;opacity:.15;display:block}
    .empty-cart h2{font-size:22px;font-weight:700;color:var(--dark);margin-bottom:8px}
    .empty-cart p{font-size:14px;margin-bottom:28px;color:var(--gray)}
    .btn-shop{display:inline-flex;align-items:center;gap:8px;padding:13px 30px;background:var(--ig-gradient);color:#fff;border-radius:10px;font-weight:700;font-size:15px;transition:opacity .2s}
    .btn-shop:hover{opacity:.9}

    /* ── RESPONSIVE ── */
    @media(max-width:1024px){
        .cart-layout{grid-template-columns:1fr 280px}
    }
    @media(max-width:860px){
        .cart-layout{grid-template-columns:1fr}
        .cart-summary{position:static}
        .cart-card-header{grid-template-columns:minmax(0,2fr) 100px 120px 100px 36px}
        .cart-row{grid-template-columns:minmax(0,2fr) 100px 120px 100px 36px}
    }
    @media(max-width:600px){
        .cart-card-header{display:none}
        .cart-row{
            grid-template-columns:1fr;
            gap:10px;
            padding:14px 16px;
            border-bottom:2px solid #fdf0f8;
        }
        .cart-product{margin-bottom:4px}
        .cart-product-img{width:60px;height:60px;min-width:60px}
        .cart-cell{
            display:flex;justify-content:space-between;
            align-items:center;font-size:13px;
            padding:4px 0;border-bottom:1px dashed #fdf0f8;
        }
        .cart-cell:last-of-type{border-bottom:none}
        .cart-cell::before{
            content:attr(data-label);
            font-size:11px;font-weight:700;
            color:var(--gray);text-transform:uppercase;letter-spacing:.5px;
        }
        .cart-cell.subtotal{font-size:14px;border-bottom:none}
        .qty-wrap{justify-content:flex-end}
        .remove-btn{margin:0;margin-left:auto;display:flex}
        .cart-footer{flex-direction:column;gap:10px;text-align:center}
    }
</style>
@endpush

@section('content')
<div class="page-banner">
    <div class="container">
        <h1><i class="fas fa-shopping-bag" style="margin-right:10px"></i>Shopping Cart</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Cart</div>
    </div>
</div>

<div class="container">
    @if(empty($items))
        <div class="empty-cart">
            <i class="fas fa-shopping-bag"></i>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything yet. Browse our collection and find something you love!</p>
            <a href="{{ route('shop') }}" class="btn-shop"><i class="fas fa-store"></i> Start Shopping</a>
        </div>
    @else
    <div class="cart-layout">

        {{-- LEFT: ITEMS --}}
        <div class="cart-card">
            {{-- Header row --}}
            <div class="cart-card-header">
                <span>Product</span>
                <span>Price</span>
                <span>Quantity</span>
                <span>Subtotal</span>
                <span></span>
            </div>

            {{-- Items --}}
            @foreach($items as $id => $item)
            <div class="cart-row">

                {{-- Product --}}
                <div class="cart-product">
                    <img src="{{ $item['image'] ?? 'https://via.placeholder.com/68' }}"
                         alt="{{ $item['name'] }}"
                         class="cart-product-img"
                         style="width:68px;height:68px;min-width:68px;object-fit:cover">
                    <div class="cart-product-info">
                        <h4>{{ $item['name'] }}</h4>
                        <span>RWF {{ number_format($item['price']) }} each</span>
                    </div>
                </div>

                {{-- Price --}}
                <div class="cart-cell" data-label="Price">
                    RWF {{ number_format($item['price']) }}
                </div>

                {{-- Quantity --}}
                <div class="cart-cell" data-label="Quantity">
                    <div class="qty-wrap">
                        <form action="{{ route('cart.update', $id) }}" method="POST" id="qty-form-{{ $id }}">
                            @csrf @method('PATCH')
                            <div class="qty-box">
                                <button type="button" onclick="changeQty({{ $id }}, -1)">−</button>
                                <input type="number" name="quantity" id="qty-{{ $id }}"
                                       value="{{ $item['quantity'] }}" min="1" max="99"
                                       onchange="document.getElementById('qty-form-{{ $id }}').submit()">
                                <button type="button" onclick="changeQty({{ $id }}, 1)">+</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Subtotal --}}
                <div class="cart-cell subtotal" data-label="Subtotal">
                    RWF {{ number_format($item['price'] * $item['quantity']) }}
                </div>

                {{-- Remove --}}
                <div>
                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="remove-btn" title="Remove item">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>

            </div>
            @endforeach

            {{-- Footer --}}
            <div class="cart-footer">
                <span class="cart-count"><strong>{{ count($items) }}</strong> item(s) in cart</span>
                <a href="{{ route('shop') }}" class="clear-cart-btn">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>

        {{-- RIGHT: SUMMARY --}}
        <div class="cart-summary">
            <div class="summary-title">Order Summary</div>

            <div class="summary-row">
                <span>Subtotal ({{ array_sum(array_column($items, 'quantity')) }} items)</span>
                <span>RWF {{ number_format($total) }}</span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span>RWF 2,000</span>
            </div>
            <div class="summary-row">
                <span>Discount</span>
                <span style="color:#10b981">— RWF 0</span>
            </div>

            <hr class="summary-divider">

            <div class="summary-total">
                <span>Total</span>
                <span>RWF {{ number_format($total + 2000) }}</span>
            </div>

            <a href="{{ route('checkout') }}" class="btn-checkout">
                <i class="fas fa-lock"></i> Proceed to Checkout
            </a>
            <a href="{{ route('shop') }}" class="btn-continue">
                <i class="fas fa-store"></i> Continue Shopping
            </a>

            <div class="payment-badges">
                <span class="pay-badge"><i class="fas fa-mobile-alt"></i> Mobile Money</span>
                <span class="pay-badge"><i class="fas fa-money-bill-wave"></i> Cash on Delivery</span>
                <span class="pay-badge"><i class="fas fa-shield-alt"></i> Secure</span>
            </div>
        </div>

    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function changeQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    const newVal = Math.max(1, Math.min(99, parseInt(input.value) + delta));
    input.value = newVal;
    document.getElementById('qty-form-' + id).submit();
}
</script>
@endpush
