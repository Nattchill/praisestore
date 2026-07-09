@extends('layouts.store')
@section('title', 'PraiseStore')

@push('styles')
<style>
    /* HERO SLIDER */
    .hero{padding:20px 0}
    .hero-slider{position:relative;border-radius:16px;overflow:hidden;height:80vh;min-height:500px}
    .slide{position:absolute;inset:0;opacity:0;transition:opacity .8s ease;display:flex;align-items:center;padding:60px 80px}
    .slide.active{opacity:1;position:relative;height:80vh;min-height:500px}
    .slide-bg{position:absolute;inset:0;background-size:cover;background-position:center}
    .slide-overlay{position:absolute;inset:0}
    .slide-content{position:relative;z-index:2;max-width:520px}
    .slide-content .tag{color:#ffd6e7;font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;display:block;margin-bottom:12px}
    .slide-content h1{font-size:42px;font-weight:800;line-height:1.2;color:#fff;margin-bottom:16px}
    .slide-content p{color:rgba(255,255,255,.8);font-size:15px;margin-bottom:28px}
    .slider-dots{position:absolute;bottom:20px;left:50%;transform:translateX(-50%);display:flex;gap:8px;z-index:10}
    .slider-dot{width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.4);cursor:pointer;transition:all .3s;border:none}
    .slider-dot.active{background:#fff;width:28px;border-radius:5px}
    .slider-arrow{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.2);border:none;color:#fff;width:44px;height:44px;border-radius:50%;font-size:16px;cursor:pointer;z-index:10;transition:background .2s;backdrop-filter:blur(4px)}
    .slider-arrow:hover{background:rgba(255,255,255,.4)}
    .slider-arrow.prev{left:16px}
    .slider-arrow.next{right:16px}

    /* LIVE NOTIFICATION TICKER */
    .notif-ticker{background:linear-gradient(90deg,#bc1888,#e1306c,#f09433);padding:10px 0;overflow:hidden;position:relative;margin-bottom:4px;border-radius:0 0 8px 8px}
    .notif-ticker::before{content:'🔴 LIVE';position:absolute;left:0;top:0;bottom:0;background:#ef4444;color:#fff;font-size:11px;font-weight:800;padding:0 14px;display:flex;align-items:center;z-index:2;letter-spacing:.5px}
    .ticker-track{display:flex;gap:0;animation:ticker 28s linear infinite;white-space:nowrap;padding-left:100px}
    .ticker-track:hover{animation-play-state:paused}
    .ticker-item{display:inline-flex;align-items:center;gap:8px;color:#fff;font-size:13px;font-weight:500;padding:0 40px;border-right:1px solid rgba(255,255,255,.2)}
    .ticker-item .dot{width:6px;height:6px;background:#4ade80;border-radius:50%;animation:pulse 1.5s infinite}
    @keyframes ticker{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
    @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.4)}}

    /* HERO TEXT (kept for side card) */
    .hero-side{display:flex;flex-direction:column;gap:20px}
    .hero-side-card{background:linear-gradient(135deg,#0f172a,#1e293b);border-radius:16px;padding:28px;color:#fff;flex:1;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:flex-end;border:1px solid rgba(255,255,255,.08)}
    .hero-side-card .tag{color:#93c5fd;font-size:12px;font-weight:600;margin-bottom:8px;display:block;position:relative;z-index:1}
    .hero-side-card h3{font-size:20px;font-weight:700;margin-bottom:14px;position:relative;z-index:1}

    /* BUTTONS */
    .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;border:none;transition:all .2s}
    .btn-primary{background:var(--ig-gradient);color:#fff}
    .btn-primary:hover{opacity:.9;transform:translateY(-1px);box-shadow:0 6px 16px rgba(225,48,108,.35)}
    .btn-dark{background:#0f172a;color:#fff}
    .btn-dark:hover{background:#1e293b}
    .btn-outline{background:transparent;color:#fff;border:2px solid rgba(255,255,255,.5)}
    .btn-outline:hover{background:rgba(255,255,255,.1);border-color:#fff}
    .btn-outline-dark{background:transparent;color:var(--dark);border:2px solid var(--border)}
    .btn-outline-dark:hover{background:var(--ig-gradient);color:#fff;border-color:transparent}
    .btn-sm{padding:8px 16px;font-size:13px}

    /* SECTION */
    .section{padding:50px 0}
    .section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:30px}
    .section-header h2{font-size:26px;font-weight:700;color:var(--dark)}
    .section-header h2 span{background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}

    /* CATEGORIES */
    .categories-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
    .category-card{border-radius:12px;overflow:hidden;position:relative;height:180px;cursor:pointer;transition:transform .2s}
    .category-card:hover{transform:translateY(-4px)}
    .category-card img{width:100%;height:100%;object-fit:cover}
    .category-card .overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.7),transparent);display:flex;align-items:flex-end;padding:16px}
    .category-card .overlay h3{color:#fff;font-size:16px;font-weight:700}
    .category-card .overlay span{color:rgba(255,255,255,.7);font-size:12px}

    /* PRODUCTS GRID */
    .products-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
    .product-card{border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:box-shadow .2s,transform .2s;background:#fff}
    .product-card:hover{box-shadow:0 8px 30px rgba(225,48,108,.18);transform:translateY(-4px)}
    .product-img{position:relative;overflow:hidden;height:240px}
    .product-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
    .product-card:hover .product-img img{transform:scale(1.05)}
    .product-badge{position:absolute;top:10px;left:10px;background:var(--primary);color:#fff;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px}
    .product-actions{position:absolute;top:10px;right:10px;display:flex;flex-direction:column;gap:6px;opacity:0;transition:opacity .2s}
    .product-card:hover .product-actions{opacity:1}
    .product-actions a{width:34px;height:34px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--dark);box-shadow:0 2px 8px rgba(0,0,0,.15);transition:background .2s,color .2s}
    .product-actions a:hover{background:var(--primary);color:#fff}
    .product-info{padding:14px}
    .product-info .cat{font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px}
    .product-info h3{font-size:14px;font-weight:600;color:var(--dark);margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .product-price{display:flex;align-items:center;gap:8px;margin-bottom:12px}
    .product-price .price{font-size:16px;font-weight:700;background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .product-price .old{font-size:13px;color:var(--gray);text-decoration:line-through}
    .add-to-cart{width:100%;padding:9px;background:var(--ig-gradient);color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:6px}
    .add-to-cart:hover{opacity:.9;box-shadow:0 4px 12px rgba(225,48,108,.3)}

    /* BANNER */
    .promo-banner{background:var(--ig-gradient);border-radius:16px;padding:40px;display:flex;align-items:center;justify-content:space-between;margin:20px 0;position:relative;overflow:hidden}
    .promo-banner::before{content:'';position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(255,255,255,.06);border-radius:50%}
    .promo-banner h2{font-size:28px;font-weight:800;color:#fff}
    .promo-banner p{color:rgba(255,255,255,.8);margin-top:6px}

    @media(max-width:1024px){
        .products-grid{grid-template-columns:repeat(3,1fr)}
        .categories-grid{grid-template-columns:repeat(2,1fr)}
    }
    @media(max-width:768px){
        .hero-slider{height:50vh;min-height:360px}
        .slide{padding:30px 24px}
        .slide.active{height:50vh;min-height:360px}
        .slide-content h1{font-size:26px}
        .products-grid{grid-template-columns:repeat(2,1fr)}
        .promo-banner{flex-direction:column;gap:16px;text-align:center}
    }
    @media(max-width:480px){
        .products-grid{grid-template-columns:1fr}
        .categories-grid{grid-template-columns:1fr 1fr}
    }
</style>
@endpush

@section('content')
<div class="container">

    {{-- LIVE NOTIFICATION TICKER --}}
    <div class="notif-ticker">
        <div class="ticker-track" id="tickerTrack">
            @foreach($newArrivals->take(6) as $p)
            <span class="ticker-item"><span class="dot"></span> 🆕 Just Added: <strong>{{ $p->name }}</strong> — RWF {{ number_format($p->price) }}</span>
            @endforeach
            @foreach($featured->take(4) as $p)
            <span class="ticker-item"><span class="dot"></span> 🔥 Trending: <strong>{{ $p->name }}</strong> — RWF {{ number_format($p->price) }}</span>
            @endforeach
            {{-- duplicate for seamless loop --}}
            @foreach($newArrivals->take(6) as $p)
            <span class="ticker-item"><span class="dot"></span> 🆕 Just Added: <strong>{{ $p->name }}</strong> — RWF {{ number_format($p->price) }}</span>
            @endforeach
            @foreach($featured->take(4) as $p)
            <span class="ticker-item"><span class="dot"></span> 🔥 Trending: <strong>{{ $p->name }}</strong> — RWF {{ number_format($p->price) }}</span>
            @endforeach
        </div>
    </div>

    {{-- HERO SLIDER --}}
    <div class="hero" style="padding:0 0 20px">
        <div class="hero-slider" id="heroSlider">

            {{-- Slide 1 --}}
            <div class="slide active">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1400&q=80')"></div>
                <div class="slide-overlay" style="background:linear-gradient(135deg,rgba(188,24,136,.85) 0%,rgba(225,48,108,.6) 60%,rgba(240,148,51,.2) 100%)"></div>
                <div class="slide-content">
                    <span class="tag">✨ Best Selling of 2026</span>
                    <h1>Discover Your Best<br>Fitting Clothes</h1>
                    <p>Shop the latest fashion trends in and out of Rwanda.<br>Quality, style, and affordability in one place.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Shop Now &nbsp;<i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="slide">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1400&q=80')"></div>
                <div class="slide-overlay" style="background:linear-gradient(135deg,rgba(45,27,61,.88) 0%,rgba(193,53,132,.55) 60%,transparent 100%)"></div>
                <div class="slide-content">
                    <span class="tag">🔥 New Arrivals</span>
                    <h1>Fresh Styles<br>Just Landed</h1>
                    <p>Be the first to wear the newest collections.<br>Exclusive pieces, limited stock.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Explore New <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="slide">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=1400&q=80')"></div>
                <div class="slide-overlay" style="background:linear-gradient(135deg,rgba(45,27,61,.88) 0%,rgba(220,39,67,.6) 60%,rgba(240,148,51,.2) 100%)"></div>
                <div class="slide-content">
                    <span class="tag">🎉 Up to 10% Off</span>
                    <h1>Summer Sale<br>Is Here!</h1>
                    <p>Limited time deals on selected fashion items.<br>Don't miss out on these amazing prices.</p>
                    <a href="{{ route('shop') }}" class="btn" style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743);color:#fff">Shop the Sale <i class="fas fa-tag"></i></a>
                </div>
            </div>

            {{-- Slide 4 --}}
            <div class="slide">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1400&q=80')"></div>
                <div class="slide-overlay" style="background:linear-gradient(135deg,rgba(45,27,61,.9) 0%,rgba(188,24,136,.65) 60%,rgba(230,104,60,.2) 100%)"></div>
                <div class="slide-content">
                    <span class="tag">👗 Women's Collection</span>
                    <h1>Elegance Meets<br>Everyday Style</h1>
                    <p>Curated women's fashion for every occasion.<br>From casual to formal, we have it all.</p>
                    <a href="{{ route('shop') }}?category=women" class="btn" style="background:linear-gradient(45deg,#cc2366,#bc1888);color:#fff">Shop Women <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <button class="slider-arrow prev" onclick="changeSlide(-1)"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-arrow next" onclick="changeSlide(1)"><i class="fas fa-chevron-right"></i></button>
            <div class="slider-dots" id="sliderDots"></div>
        </div>
    </div>

    {{-- CATEGORIES --}}
    <div class="section">
        <div class="section-header">
            <h2>Browse <span>Categories</span></h2>
            <a href="{{ route('shop') }}" class="btn btn-outline-dark btn-sm">View All</a>
        </div>
        <div class="categories-grid">
            @foreach($categories as $category)
            <a href="{{ route('shop') }}?category={{ $category->slug }}" class="category-card">
                <img src="{{ $category->image }}" alt="{{ $category->name }}">
                <div class="overlay">
                    <div>
                        <h3>{{ $category->name }}</h3>
                        <span>{{ $category->products_count }} Products</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- FEATURED PRODUCTS --}}
    <div class="section">
        <div class="section-header">
            <h2>Featured <span>Products</span></h2>
            <a href="{{ route('shop') }}" class="btn btn-outline-dark btn-sm">View All</a>
        </div>
        <div class="products-grid">
            @foreach($featured as $product)
            <div class="product-card">
                <div class="product-img">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $product->name }}">
                    @if($product->old_price)
                        <span class="product-badge">-{{ $product->discount_percent }}%</span>
                    @endif
                    <div class="product-actions">
                        <a href="{{ route('product.show', $product->slug) }}" title="View"><i class="fas fa-eye"></i></a>
                    </div>
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
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="add-to-cart"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- PROMO BANNER --}}
    <div class="promo-banner">
        <div>
            <h2>🎉 Special Offer – Up to 10% Off!</h2>
            <p>Limited time deals on selected fashion items. Don't miss out!</p>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-dark">Shop the Sale <i class="fas fa-arrow-right"></i></a>
    </div>

    {{-- NEW ARRIVALS --}}
    <div class="section">
        <div class="section-header">
            <h2>New <span>Arrivals</span></h2>
            <a href="{{ route('shop') }}" class="btn btn-outline-dark btn-sm">View All</a>
        </div>
        <div class="products-grid">
            @foreach($newArrivals->take(8) as $product)
            <div class="product-card">
                <div class="product-img">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $product->name }}">
                    <span class="product-badge" style="background:#10b981">New</span>
                    <div class="product-actions">
                        <a href="{{ route('product.show', $product->slug) }}" title="View"><i class="fas fa-eye"></i></a>
                    </div>
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
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="add-to-cart"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- FEATURES --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;padding:30px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:40px;background:#fff;border-radius:12px;padding:24px 30px">
        @foreach([['fas fa-shipping-fast','Free Shipping','On orders over RWF 50,000'],['fas fa-undo','Easy Returns','30-day return policy'],['fas fa-lock','Secure Payment','100% secure transactions'],['fas fa-headset','24/7 Support','Always here to help']] as $f)
        <div style="display:flex;align-items:center;gap:14px">
            <i class="{{ $f[0] }}" style="font-size:28px;color:var(--primary)"></i>
            <div>
                <strong style="display:block;font-size:14px;color:var(--dark)">{{ $f[1] }}</strong>
                <span style="font-size:12px;color:var(--gray)">{{ $f[2] }}</span>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
    const slides = document.querySelectorAll('#heroSlider .slide');
    const dotsContainer = document.getElementById('sliderDots');
    let current = 0, timer;

    slides.forEach((_,i) => {
        const d = document.createElement('button');
        d.className = 'slider-dot' + (i===0?' active':'');
        d.onclick = () => goTo(i);
        dotsContainer.appendChild(d);
    });

    function goTo(n){
        slides[current].classList.remove('active');
        slides[current].style.position='absolute';
        dotsContainer.children[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].style.position='relative';
        slides[current].classList.add('active');
        dotsContainer.children[current].classList.add('active');
    }

    window.changeSlide = (dir) => { clearInterval(timer); goTo(current+dir); startAuto(); };

    function startAuto(){ timer = setInterval(() => goTo(current+1), 5000); }
    startAuto();
})();
</script>
@endpush
