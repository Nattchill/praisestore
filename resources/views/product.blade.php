@extends('layouts.store')
@section('title', $product->name . ' – PraiseStore')

@push('styles')
<style>
    .page-banner{background:var(--ig-gradient);color:#fff;padding:30px 0;margin-bottom:30px}
    .page-banner .breadcrumb{font-size:13px;color:rgba(255,255,255,.6)}
    .page-banner .breadcrumb a{color:#ffd6e7}
    .product-detail{display:grid;grid-template-columns:1fr 1fr;gap:50px;padding-bottom:50px}
    .product-gallery img{width:100%;border-radius:12px;object-fit:cover;max-height:500px;box-shadow:0 8px 32px rgba(225,48,108,.15)}
    .product-detail-info h1{font-size:28px;font-weight:700;color:var(--dark);margin-bottom:10px}
    .product-detail-info .cat-tag{display:inline-block;background:var(--light);color:var(--primary);font-size:12px;padding:4px 10px;border-radius:20px;margin-bottom:14px;font-weight:600}
    .price-block .price{font-size:30px;font-weight:800;background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .price-block .old{font-size:18px;color:var(--gray);text-decoration:line-through}
    .price-block .badge{background:var(--ig-gradient);color:#fff;font-size:12px;padding:4px 10px;border-radius:20px;font-weight:700}
    .product-desc{font-size:14px;color:var(--gray);line-height:1.8;margin-bottom:24px;padding:16px;background:var(--light);border-radius:8px;border-left:3px solid var(--primary)}
    .qty-input button:hover{background:var(--ig-gradient);color:#fff}
    .btn-dark{background:var(--nav-bg);color:#fff}
    .btn-dark:hover{opacity:.85}
    .related-section h2{font-size:22px;font-weight:700;margin-bottom:20px;background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .product-card:hover{box-shadow:0 8px 30px rgba(225,48,108,.18);transform:translateY(-4px)}
    .product-price .price{font-size:15px;font-weight:700;background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    @media(max-width:768px){.product-detail{grid-template-columns:1fr}.related-grid{grid-template-columns:repeat(2,1fr)}}
</style>
@endpush

@section('content')
<div class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('shop') }}">Shop</a> /
            <a href="{{ route('shop') }}?category={{ $product->category->slug }}">{{ $product->category->name }}</a> /
            {{ $product->name }}
        </div>
    </div>
</div>

<div class="container">
    <div class="product-detail">
        <div class="product-gallery">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/600x500' }}" alt="{{ $product->name }}">
        </div>
        <div class="product-detail-info">
            <span class="cat-tag">{{ $product->category->name }}</span>
            <h1>{{ $product->name }}</h1>
            <div class="price-block">
                <span class="price">RWF {{ number_format($product->price) }}</span>
                @if($product->old_price)
                    <span class="old">RWF {{ number_format($product->old_price) }}</span>
                    <span class="badge">-{{ $product->discount_percent }}% OFF</span>
                @endif
            </div>
            <div class="stock-info {{ $product->stock > 0 ? 'in' : 'out' }}">
                <i class="fas fa-{{ $product->stock > 0 ? 'check-circle' : 'times-circle' }}"></i>
                {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' available)' : 'Out of Stock' }}
            </div>
            @if($product->description)
                <div class="product-desc">{{ $product->description }}</div>
            @endif

            @if($product->stock > 0)
            @auth
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="qty-row">
                    <label>Quantity:</label>
                    <div class="qty-input">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $product->stock }}">
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
                    <a href="{{ route('cart') }}" class="btn btn-dark"><i class="fas fa-bolt"></i> View Cart</a>
                </div>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary" style="text-decoration:none"><i class="fas fa-lock"></i> Login to Add to Cart</a>
            @endauth
            @else
                <p style="color:#ef4444;font-weight:600"><i class="fas fa-times-circle"></i> This product is currently out of stock.</p>
            @endif
        </div>
    </div>

    @if($related->count())
    <div class="related-section">
        <h2>Related Products</h2>
        <div class="related-grid">
            @foreach($related as $p)
            <div class="product-card">
                <div class="product-img">
                    <img src="{{ $p->image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $p->name }}">
                </div>
                <div class="product-info">
                    <h3><a href="{{ route('product.show', $p->slug) }}">{{ $p->name }}</a></h3>
                    <div class="product-price">
                        <span class="price">RWF {{ number_format($p->price) }}</span>
                    </div>
                    <form action="{{ route('cart.add') }}" method="POST" style="margin-top:8px">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        @auth
                        <button type="submit" style="width:100%;padding:8px;background:var(--dark);color:#fff;border:none;border-radius:6px;font-size:12px;cursor:pointer">Add to Cart</button>
                        @else
                        <a href="{{ route('login') }}" style="display:block;width:100%;padding:8px;background:var(--dark);color:#fff;border-radius:6px;font-size:12px;text-align:center;text-decoration:none"><i class="fas fa-lock"></i> Login to Add</a>
                        @endauth
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    const val = parseInt(input.value) + delta;
    const max = parseInt(input.max);
    if (val >= 1 && val <= max) input.value = val;
}
</script>
@endpush
