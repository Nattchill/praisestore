@extends('admin.layout')
@section('title', 'Products')
@section('page-title', 'Product Management')
@section('breadcrumb', 'Products')

@push('styles')
<style>
    .toolbar{display:flex;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap}
    .toolbar-search{display:flex;align-items:center;gap:0;flex:1;min-width:220px;max-width:360px}
    .toolbar-search input{flex:1;padding:9px 14px;border:1px solid var(--border);border-right:none;border-radius:8px 0 0 8px;font-size:13px;outline:none}
    .toolbar-search input:focus{border-color:var(--primary)}
    .toolbar-search button{padding:9px 14px;background:var(--primary);color:#fff;border:none;border-radius:0 8px 8px 0;cursor:pointer;font-size:13px}
    .toolbar-filter select{padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;outline:none;background:#fff;cursor:pointer}
    .toolbar-filter select:focus{border-color:var(--primary)}
    .stats-row{display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap}
    .mini-stat{background:#fff;border:1px solid var(--border);border-radius:10px;padding:12px 18px;display:flex;align-items:center;gap:10px;flex:1;min-width:140px}
    .mini-stat-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
    .mini-stat-val{font-size:18px;font-weight:800;color:var(--dark)}
    .mini-stat-lbl{font-size:11px;color:var(--gray)}
    .product-img-cell{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid var(--border);flex-shrink:0}
    .product-img-placeholder{width:52px;height:52px;border-radius:8px;border:1px solid var(--border);background:var(--light);display:flex;align-items:center;justify-content:center;color:var(--gray);font-size:18px;flex-shrink:0}
    .stock-bar{width:60px;height:5px;background:#e5e7eb;border-radius:3px;margin-top:4px;overflow:hidden}
    .stock-bar-fill{height:100%;border-radius:3px;background:var(--success)}
    .stock-bar-fill.low{background:var(--warning)}
    .stock-bar-fill.out{background:var(--danger)}
    .empty-state{text-align:center;padding:60px 20px;color:var(--gray)}
    .empty-state i{font-size:48px;opacity:.2;margin-bottom:16px;display:block}
</style>
@endpush

@section('content')

{{-- MINI STATS --}}
<div class="stats-row">
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-tshirt"></i></div>
        <div><div class="mini-stat-val">{{ \App\Models\Product::count() }}</div><div class="mini-stat-lbl">Total Products</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#f0fdf4;color:#22c55e"><i class="fas fa-check-circle"></i></div>
        <div><div class="mini-stat-val">{{ \App\Models\Product::where('is_active', true)->count() }}</div><div class="mini-stat-lbl">Active</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fff7ed;color:var(--primary)"><i class="fas fa-star"></i></div>
        <div><div class="mini-stat-val">{{ \App\Models\Product::where('featured', true)->count() }}</div><div class="mini-stat-lbl">Featured</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fef2f2;color:#ef4444"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="mini-stat-val">{{ \App\Models\Product::where('stock', 0)->count() }}</div><div class="mini-stat-lbl">Out of Stock</div></div>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="toolbar">
    <form method="GET" action="{{ route('admin.products') }}" style="display:flex;gap:12px;flex:1;flex-wrap:wrap">
        <div class="toolbar-search">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        <div class="toolbar-filter">
            <select name="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="toolbar-filter">
            <select name="status" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                <option value="featured" {{ request('status')=='featured'?'selected':'' }}>Featured</option>
                <option value="out_of_stock" {{ request('status')=='out_of_stock'?'selected':'' }}>Out of Stock</option>
            </select>
        </div>
        @if(request('search') || request('category') || request('status'))
            <a href="{{ route('admin.products') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Clear</a>
        @endif
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-tshirt" style="color:var(--primary)"></i> Products
            <span style="font-size:13px;font-weight:400;color:var(--gray);margin-left:6px">({{ $products->total() }} total)</span>
        </h2>
        <div style="font-size:13px;color:var(--gray)">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</div>
    </div>

    @if($products->isEmpty())
        <div class="empty-state">
            <i class="fas fa-tshirt"></i>
            <p style="font-size:15px;font-weight:600;margin-bottom:8px">No products found</p>
            <p style="margin-bottom:20px">Try adjusting your search or filters.</p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add First Product</a>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:52px">#</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Added</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $i => $product)
                <tr>
                    <td style="color:var(--gray);font-size:12px">{{ $products->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px">
                            @if($product->image)
                                <img src="{{ $product->image }}" class="product-img-cell" alt="{{ $product->name }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="product-img-placeholder" style="display:none"><i class="fas fa-tshirt"></i></div>
                            @else
                                <div class="product-img-placeholder"><i class="fas fa-tshirt"></i></div>
                            @endif
                            <div>
                                <div style="font-size:13.5px;font-weight:700;color:var(--dark);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $product->name }}</div>
                                <div style="font-size:11px;color:var(--gray);margin-top:2px">{{ Str::limit($product->description, 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="background:#f1f5f9;color:#475569;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">
                            {{ $product->category->name }}
                        </span>
                    </td>
                    <td>
                        <div style="font-size:14px;font-weight:700;color:var(--dark)">RWF {{ number_format($product->price) }}</div>
                        @if($product->old_price)
                            <div style="font-size:11px;color:var(--gray);text-decoration:line-through">RWF {{ number_format($product->old_price) }}</div>
                            <div style="font-size:11px;color:#22c55e;font-weight:600">-{{ $product->discount_percent }}% off</div>
                        @endif
                    </td>
                    <td>
                        @php $pct = $product->stock > 0 ? min(100, ($product->stock / 50) * 100) : 0; @endphp
                        <div style="font-size:13px;font-weight:700;color:{{ $product->stock == 0 ? '#ef4444' : ($product->stock <= 5 ? '#f59e0b' : '#22c55e') }}">
                            {{ $product->stock }} units
                        </div>
                        <div class="stock-bar">
                            <div class="stock-bar-fill {{ $product->stock == 0 ? 'out' : ($product->stock <= 5 ? 'low' : '') }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $product->is_active ? 'badge-active' : 'badge-inactive' }}">
                            <i class="fas fa-circle" style="font-size:7px"></i>
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        @if($product->featured)
                            <span class="badge" style="background:#fef3c7;color:#92400e"><i class="fas fa-star" style="font-size:9px"></i> Yes</span>
                        @else
                            <span style="color:var(--gray);font-size:12px">—</span>
                        @endif
                    </td>
                    <td style="color:var(--gray);font-size:12px">{{ $product->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center">
                            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn btn-secondary btn-sm btn-icon" title="View in store"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-info btn-sm btn-icon" title="Edit" style="background:#eff6ff;color:#3b82f6;border:1px solid #bfdbfe"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.products.delete', $product) }}" method="POST" onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $products->links() }}</div>
    @endif
</div>
@endsection
