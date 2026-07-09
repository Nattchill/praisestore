@extends('layouts.store')
@section('title', 'Shop – PraiseStore')

@push('styles')
<style>
    .page-banner{background:var(--ig-gradient);color:#fff;padding:30px 0;margin-bottom:30px}
    .page-banner h1{font-size:28px;font-weight:700}
    .page-banner .breadcrumb{font-size:13px;color:rgba(255,255,255,.6);margin-top:6px}
    .page-banner .breadcrumb a{color:#ffd6e7}
    .shop-layout{display:grid;grid-template-columns:240px 1fr;gap:30px;padding-bottom:50px}
    .sidebar{background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;height:fit-content}
    .sidebar h3{font-size:15px;font-weight:700;color:var(--dark);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border)}
    .filter-list{list-style:none}
    .filter-list li{margin-bottom:6px}
    .filter-list li a{font-size:13px;color:var(--gray);display:flex;justify-content:space-between;padding:6px 8px;border-radius:6px;transition:all .2s}
    .filter-list li a:hover,.filter-list li a.active{background:var(--ig-gradient);color:#fff}
    .shop-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
    .shop-header p{font-size:13px;color:var(--gray)}
    .sort-select{border:1px solid var(--border);padding:8px 12px;border-radius:6px;font-size:13px;cursor:pointer}
    .products-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
    .product-card{border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:box-shadow .2s,transform .2s;background:#fff}
    .product-card:hover{box-shadow:0 8px 30px rgba(225,48,108,.18);transform:translateY(-4px)}
    .product-img{position:relative;overflow:hidden;height:220px}
    .product-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
    .product-card:hover .product-img img{transform:scale(1.05)}
    .product-badge{position:absolute;top:10px;left:10px;background:var(--ig-gradient);color:#fff;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px}
    .product-info{padding:14px}
    .product-info .cat{font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px}
    .product-info h3{font-size:14px;font-weight:600;color:var(--dark);margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .product-price{display:flex;align-items:center;gap:8px;margin-bottom:12px}
    .product-price .price{font-size:16px;font-weight:700;background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .product-price .old{font-size:13px;color:var(--gray);text-decoration:line-through}
    .add-to-cart{width:100%;padding:9px;background:var(--ig-gradient);color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;transition:opacity .2s;display:flex;align-items:center;justify-content:center;gap:6px}
    .add-to-cart:hover{opacity:.85}
    .pagination-wrap{margin-top:30px;display:flex;justify-content:center}
    @media(max-width:900px){.shop-layout{grid-template-columns:1fr}.sidebar{display:none}.products-grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:480px){.products-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Shop All Products</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Shop</div>
    </div>
</div>

<div class="container">
    <div class="shop-layout">
        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <h3>Categories</h3>
            <ul class="filter-list">
                <li><a href="{{ route('shop') }}" class="{{ !request('category') ? 'active' : '' }}">All Products <span>{{ \App\Models\Product::count() }}</span></a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('shop') }}?category={{ $cat->slug }}{{ request('search') ? '&search='.request('search') : '' }}" class="{{ request('category') == $cat->slug ? 'active' : '' }}">
                        {{ $cat->name }} <span>{{ $cat->products->count() }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- PRODUCTS --}}
        <div>
            <div class="shop-header">
                <p>Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> products</p>
                <form method="GET" action="{{ route('shop') }}">
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="">Sort: Latest</option>
                        <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
                <div style="text-align:center;padding:60px 20px;color:var(--gray)">
                    <i class="fas fa-search" style="font-size:48px;margin-bottom:16px;opacity:.3"></i>
                    <p>No products found. <a href="{{ route('shop') }}" style="color:var(--primary)">Clear filters</a></p>
                </div>
            @else
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card">
                    <div class="product-img">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $product->name }}">
                        @if($product->old_price)
                            <span class="product-badge">-{{ $product->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="product-info">
                        <div class="cat">{{ $product->category->name }}</div>
                        <h3><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                        <div class="product-price">
                            <span class="price">RWF {{ number_format($product->price) }}</span>
                            @if($product->old_price)
                                <span class="old">RWF {{ number_format($product->old_price) }}</span>
                            @endif
                        </div>
                        @auth
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="add-to-cart"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="add-to-cart" style="text-decoration:none"><i class="fas fa-lock"></i> Login to Add</a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
