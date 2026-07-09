@extends('layouts.store')
@section('title', $product->name . ' – PraiseStore')

@push('styles')
<style>
    .pd-wrap{max-width:1100px;margin:0 auto;padding:30px 20px 60px}
    .pd-breadcrumb{font-size:13px;color:var(--gray);margin-bottom:28px;display:flex;align-items:center;gap:6px;flex-wrap:wrap}
    .pd-breadcrumb a{color:var(--gray);text-decoration:none;transition:color .2s}
    .pd-breadcrumb a:hover{color:var(--primary)}
    .pd-breadcrumb i{font-size:10px;color:#d1d5db}

    .pd-grid{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:start}

    /* Gallery */
    .pd-gallery{position:sticky;top:80px}
    .pd-main-img{width:100%;aspect-ratio:4/5;object-fit:cover;border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,.12)}
    .pd-img-wrap{position:relative;border-radius:16px;overflow:hidden;background:#f9fafb}
    .pd-badge{position:absolute;top:16px;left:16px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px}
    .pd-badge-new{position:absolute;top:16px;left:16px;background:#10b981;color:#fff;font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px}

    /* Info */
    .pd-info{padding-top:8px}
    .pd-cat{display:inline-block;font-size:12px;font-weight:700;color:var(--primary);background:#fff0f5;padding:4px 12px;border-radius:20px;margin-bottom:14px;text-transform:uppercase;letter-spacing:.5px;text-decoration:none}
    .pd-cat:hover{background:var(--primary);color:#fff}
    .pd-name{font-size:30px;font-weight:800;color:#0f172a;line-height:1.25;margin-bottom:16px}
    .pd-price-row{display:flex;align-items:center;gap:14px;margin-bottom:20px;flex-wrap:wrap}
    .pd-price{font-size:32px;font-weight:800;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .pd-old{font-size:18px;color:#9ca3af;text-decoration:line-through;font-weight:500}
    .pd-discount{background:linear-gradient(45deg,#dc2743,#bc1888);color:#fff;font-size:12px;font-weight:700;padding:4px 10px;border-radius:20px}

    .pd-stock{display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;padding:7px 14px;border-radius:8px;margin-bottom:22px}
    .pd-stock.in{background:#d1fae5;color:#065f46}
    .pd-stock.out{background:#fee2e2;color:#991b1b}

    .pd-desc{font-size:14px;color:#4b5563;line-height:1.85;margin-bottom:28px;padding:18px 20px;background:#f8fafc;border-radius:12px;border-left:4px solid var(--primary)}

    .pd-divider{height:1px;background:#f1f5f9;margin:24px 0}

    .pd-qty-label{font-size:13px;font-weight:700;color:#374151;margin-bottom:10px}
    .pd-qty-row{display:flex;align-items:center;gap:0;border:1.5px solid #e5e7eb;border-radius:10px;overflow:hidden;width:fit-content;margin-bottom:22px}
    .pd-qty-row button{width:42px;height:42px;background:#f9fafb;border:none;font-size:18px;cursor:pointer;color:#374151;transition:background .2s;font-weight:600}
    .pd-qty-row button:hover{background:#f3f4f6}
    .pd-qty-row input{width:56px;height:42px;border:none;border-left:1.5px solid #e5e7eb;border-right:1.5px solid #e5e7eb;text-align:center;font-size:15px;font-weight:700;color:#0f172a;outline:none}

    .pd-actions{display:flex;gap:12px;flex-wrap:wrap}
    .pd-btn-cart{flex:1;min-width:160px;padding:14px 24px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s}
    .pd-btn-cart:hover{opacity:.9;transform:translateY(-2px);box-shadow:0 8px 24px rgba(225,48,108,.35)}
    .pd-btn-view{padding:14px 20px;background:#0f172a;color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;text-decoration:none;transition:all .2s}
    .pd-btn-view:hover{background:#1e293b;transform:translateY(-2px)}
    .pd-btn-login{flex:1;padding:14px 24px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;border-radius:12px;font-size:15px;font-weight:700;text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px}

    .pd-meta{margin-top:28px;display:flex;flex-direction:column;gap:10px}
    .pd-meta-row{display:flex;align-items:center;gap:10px;font-size:13px;color:#6b7280}
    .pd-meta-row i{width:18px;color:var(--primary)}
    .pd-meta-row strong{color:#374151}

    /* Related */
    .related-wrap{max-width:1100px;margin:0 auto;padding:0 20px 60px}
    .related-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
    .related-header h2{font-size:22px;font-weight:800;color:#0f172a}
    .related-header h2 span{background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .related-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}

    .rc{border-radius:14px;overflow:hidden;background:#fff;border:1px solid #f1f5f9;transition:all .25s;box-shadow:0 2px 8px rgba(0,0,0,.04)}
    .rc:hover{transform:translateY(-5px);box-shadow:0 12px 32px rgba(225,48,108,.15);border-color:#fce7f3}
    .rc-img{position:relative;aspect-ratio:4/3;overflow:hidden;background:#f9fafb}
    .rc-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
    .rc:hover .rc-img img{transform:scale(1.06)}
    .rc-body{padding:14px}
    .rc-cat{font-size:11px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px}
    .rc-name{font-size:14px;font-weight:700;color:#0f172a;margin-bottom:8px;line-height:1.35}
    .rc-name a{text-decoration:none;color:inherit}
    .rc-name a:hover{color:var(--primary)}
    .rc-price{font-size:15px;font-weight:800;background:linear-gradient(45deg,#dc2743,#bc1888);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:10px}
    .rc-btn{width:100%;padding:9px;background:#0f172a;color:#fff;border:none;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;transition:background .2s}
    .rc-btn:hover{background:linear-gradient(45deg,#dc2743,#bc1888)}
    .rc-btn a{color:#fff;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px}

    @media(max-width:900px){
        .pd-grid{grid-template-columns:1fr;gap:28px}
        .pd-gallery{position:static}
        .related-grid{grid-template-columns:repeat(2,1fr)}
    }
    @media(max-width:480px){
        .pd-name{font-size:24px}
        .pd-price{font-size:26px}
        .related-grid{grid-template-columns:repeat(2,1fr);gap:12px}
    }
</style>
@endpush

@section('content')
<div class="pd-wrap">

    {{-- Breadcrumb --}}
    <div class="pd-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('shop') }}">Shop</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('shop') }}?category={{ $product->category->slug }}">{{ $product->category->name }}</a>
        <i class="fas fa-chevron-right"></i>
        <span style="color:#0f172a;font-weight:600">{{ $product->name }}</span>
    </div>

    <div class="pd-grid">

        {{-- Gallery --}}
        <div class="pd-gallery">
            <div class="pd-img-wrap">
                <img class="pd-main-img"
                     src="{{ $product->image ?? 'https://via.placeholder.com/600x750?text=No+Image' }}"
                     alt="{{ $product->name }}">
                @if($product->old_price)
                    <span class="pd-badge">-{{ $product->discount_percent }}% OFF</span>
                @else
                    <span class="pd-badge-new">New</span>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div class="pd-info">
            <a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="pd-cat">
                {{ $product->category->name }}
            </a>
            <h1 class="pd-name">{{ $product->name }}</h1>

            <div class="pd-price-row">
                <span class="pd-price">RWF {{ number_format($product->price) }}</span>
                @if($product->old_price)
                    <span class="pd-old">RWF {{ number_format($product->old_price) }}</span>
                    <span class="pd-discount">Save {{ $product->discount_percent }}%</span>
                @endif
            </div>

            <div class="pd-stock {{ $product->stock > 0 ? 'in' : 'out' }}">
                <i class="fas fa-{{ $product->stock > 0 ? 'check-circle' : 'times-circle' }}"></i>
                {{ $product->stock > 0 ? 'In Stock — ' . $product->stock . ' available' : 'Out of Stock' }}
            </div>

            @if($product->description)
                <div class="pd-desc">{{ $product->description }}</div>
            @endif

            <div class="pd-divider"></div>

            @if($product->stock > 0)
                @auth
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="pd-qty-label">Quantity</div>
                    <div class="pd-qty-row">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $product->stock }}">
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                    <div class="pd-actions">
                        <button type="submit" class="pd-btn-cart">
                            <i class="fas fa-shopping-bag"></i> Add to Cart
                        </button>
                        <a href="{{ route('cart') }}" class="pd-btn-view">
                            <i class="fas fa-bolt"></i> View Cart
                        </a>
                    </div>
                </form>
                @else
                <div class="pd-actions">
                    <a href="{{ route('login') }}" class="pd-btn-login">
                        <i class="fas fa-lock"></i> Login to Add to Cart
                    </a>
                </div>
                @endauth
            @else
                <p style="color:#ef4444;font-weight:600;font-size:14px"><i class="fas fa-times-circle"></i> This product is currently out of stock.</p>
            @endif

            <div class="pd-meta">
                <div class="pd-meta-row"><i class="fas fa-truck"></i> <span>Free delivery on orders over <strong>RWF 50,000</strong></span></div>
                <div class="pd-meta-row"><i class="fas fa-undo"></i> <span>Easy returns within <strong>7 days</strong></span></div>
                <div class="pd-meta-row"><i class="fas fa-shield-alt"></i> <span><strong>Secure checkout</strong> — Cash on Delivery & Mobile Money</span></div>
            </div>
        </div>
    </div>
</div>

@if($related->count())
<div class="related-wrap">
    <div class="related-header">
        <h2>Related <span>Products</span></h2>
        <a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="btn btn-outline-dark btn-sm">View All</a>
    </div>
    <div class="related-grid">
        @foreach($related as $p)
        <div class="rc">
            <div class="rc-img">
                <img src="{{ $p->image ?? 'https://via.placeholder.com/400x300?text=No+Image' }}" alt="{{ $p->name }}">
            </div>
            <div class="rc-body">
                <div class="rc-cat">{{ $p->category->name }}</div>
                <div class="rc-name"><a href="{{ route('product.show', $p->slug) }}">{{ $p->name }}</a></div>
                <div class="rc-price">RWF {{ number_format($p->price) }}</div>
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                    @auth
                    <button type="submit" class="rc-btn"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
                    @else
                    <div class="rc-btn"><a href="{{ route('login') }}"><i class="fas fa-lock"></i> Login to Add</a></div>
                    @endauth
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    const val = parseInt(input.value) + delta;
    if (val >= 1 && val <= parseInt(input.max)) input.value = val;
}
</script>
@endpush
