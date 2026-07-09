@extends('admin.layout')
@section('title', isset($product) ? 'Edit: ' . $product->name : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add New Product')
@section('breadcrumb', isset($product) ? 'Products / Edit' : 'Products / Add')

@push('styles')
<style>
    .form-layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
    .form-section{background:#fff;border:1px solid var(--border);border-radius:12px;padding:22px;margin-bottom:20px}
    .form-section-title{font-size:14px;font-weight:700;color:var(--dark);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px}
    .form-section-title i{color:var(--primary)}
    .form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
    .form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .char-count{font-size:11px;color:var(--gray);text-align:right;margin-top:3px}
    .char-count.warn{color:var(--warning)}
    .char-count.over{color:var(--danger)}

    /* Upload zone hover */
    #uploadZone:hover{border-color:var(--primary)!important;background:#fffbf0!important}

    /* Price calculator */
    .discount-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:#d1fae5;color:#065f46;border-radius:8px;font-size:13px;font-weight:700;margin-top:8px}
    .discount-badge.hidden{display:none}

    /* Toggle switch */
    .toggle-wrap{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:var(--light);border-radius:8px;border:1px solid var(--border)}
    .toggle-label{font-size:13.5px;font-weight:600;color:var(--dark)}
    .toggle-desc{font-size:11px;color:var(--gray);margin-top:2px}
    .toggle{position:relative;width:44px;height:24px;flex-shrink:0}
    .toggle input{opacity:0;width:0;height:0}
    .toggle-slider{position:absolute;inset:0;background:#d1d5db;border-radius:24px;cursor:pointer;transition:.3s}
    .toggle-slider:before{content:'';position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.3s}
    .toggle input:checked + .toggle-slider{background:var(--primary)}
    .toggle input:checked + .toggle-slider:before{transform:translateX(20px)}

    /* Sticky sidebar */
    .sidebar-sticky{position:sticky;top:80px}

    @media(max-width:1024px){.form-layout{grid-template-columns:1fr}.sidebar-sticky{position:static}}
    @media(max-width:600px){.form-row-3{grid-template-columns:1fr}.form-row-2{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <a href="{{ route('admin.products') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Products</a>
    @isset($product)
        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View in Store</a>
    @endisset
</div>

<form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
    @csrf
    @isset($product) @method('PUT') @endisset

    <div class="form-layout">

        {{-- LEFT COLUMN --}}
        <div>

            {{-- BASIC INFO --}}
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-info-circle"></i> Basic Information</div>

                <div class="form-group">
                    <label>Product Name <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="name" id="productName" class="form-control" value="{{ old('name', $product->name ?? '') }}" required maxlength="255" placeholder="e.g. Floral Summer Dress" oninput="updateCharCount(this,'nameCount',255)">
                    <div class="char-count" id="nameCount">{{ strlen(old('name', $product->name ?? '')) }}/255</div>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Category <span style="color:var(--danger)">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required placeholder="0">
                        @error('stock')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="productDesc" class="form-control" rows="5" maxlength="1000" placeholder="Describe the product — material, fit, style, occasion..." oninput="updateCharCount(this,'descCount',1000)">{{ old('description', $product->description ?? '') }}</textarea>
                    <div class="char-count" id="descCount">{{ strlen(old('description', $product->description ?? '')) }}/1000</div>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- PRICING --}}
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-tag"></i> Pricing</div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Selling Price (RWF) <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="price" id="priceInput" class="form-control" value="{{ old('price', $product->price ?? '') }}" min="0" step="100" required placeholder="0" oninput="calcDiscount()">
                        @error('price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Original Price (RWF) <span style="color:var(--gray);font-weight:400;font-size:11px">optional – for discount badge</span></label>
                        <input type="number" name="old_price" id="oldPriceInput" class="form-control" value="{{ old('old_price', $product->old_price ?? '') }}" min="0" step="100" placeholder="0" oninput="calcDiscount()">
                        @error('old_price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="discount-badge {{ (old('old_price', $product->old_price ?? 0) && old('price', $product->price ?? 0)) ? '' : 'hidden' }}" id="discountBadge">
                    <i class="fas fa-percentage"></i>
                    <span id="discountText">0% discount</span>
                    <span style="font-weight:400;color:#047857">will show on product</span>
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-image"></i> Product Image</div>

                {{-- CURRENT IMAGE (edit mode) --}}
                @isset($product)
                    @if($product->image)
                    <div id="currentImgWrap" style="margin-bottom:14px">
                        <div style="font-size:12px;font-weight:600;color:var(--gray);margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px">Current Image</div>
                        <div style="position:relative;display:inline-block">
                            <img src="{{ $product->image }}"
                                 style="width:120px;height:120px;object-fit:cover;border-radius:10px;border:2px solid var(--border)"
                                 id="currentImg" alt="Current">
                            <button type="button" onclick="removeCurrentImage()"
                                style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;border-radius:50%;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" name="image" id="existingImageUrl" value="{{ $product->image }}">
                    </div>
                    @endif
                @endisset

                {{-- UPLOAD ZONE --}}
                <div id="uploadZone"
                     style="border:2px dashed var(--border);border-radius:12px;padding:30px 20px;text-align:center;cursor:pointer;transition:all .2s;background:var(--light);margin-bottom:14px"
                     onclick="document.getElementById('imageFile').click()"
                     ondragover="dragOver(event)" ondragleave="dragLeave(event)" ondrop="dropFile(event)">
                    <div id="uploadContent">
                        <i class="fas fa-cloud-upload-alt" style="font-size:36px;color:var(--primary);opacity:.7;margin-bottom:10px;display:block"></i>
                        <p style="font-size:14px;font-weight:600;color:var(--dark);margin-bottom:4px">Click to upload or drag & drop</p>
                        <p style="font-size:12px;color:var(--gray)">JPG, PNG, WEBP — max 10MB</p>
                    </div>
                    <div id="uploadPreviewWrap" style="display:none">
                        <img id="uploadPreview" style="max-height:200px;max-width:100%;border-radius:8px;object-fit:contain" alt="Preview">
                        <p id="uploadFileName" style="font-size:12px;color:var(--gray);margin-top:8px"></p>
                        <button type="button" onclick="clearUpload(event)" style="margin-top:8px;padding:5px 14px;background:#fee2e2;color:#991b1b;border:none;border-radius:6px;font-size:12px;cursor:pointer;font-weight:600">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                </div>

                <input type="file" name="image_file" id="imageFile" accept="image/jpeg,image/png,image/jpg,image/webp"
                       style="display:none" onchange="handleFileSelect(this)">
                @error('image_file')<div class="form-error" style="margin-bottom:10px">{{ $message }}</div>@enderror

                {{-- DIVIDER --}}
                <div style="display:flex;align-items:center;gap:10px;margin:14px 0">
                    <div style="flex:1;height:1px;background:var(--border)"></div>
                    <span style="font-size:12px;color:var(--gray);font-weight:600">OR USE URL</span>
                    <div style="flex:1;height:1px;background:var(--border)"></div>
                </div>

                {{-- URL FALLBACK --}}
                <div class="form-group" style="margin-bottom:0">
                    <label>Image URL <span style="color:var(--gray);font-weight:400;font-size:11px">(optional – if not uploading a file)</span></label>
                    <input type="text" name="image" id="imageUrl" class="form-control"
                           value="{{ old('image', !isset($product) ? '' : (str_starts_with($product->image ?? '', '/storage/') ? '' : ($product->image ?? ''))) }}"
                           placeholder="https://images.unsplash.com/..."
                           oninput="previewUrl(this.value)">
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="sidebar-sticky">

            {{-- PUBLISH --}}
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-rocket"></i> Publish</div>

                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px">
                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Active / Visible</div>
                            <div class="toggle-desc">Show this product in the store</div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Featured Product</div>
                            <div class="toggle-desc">Show on homepage featured section</div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured ?? false) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary" style="width:100%;padding:12px;font-size:14px;justify-content:center">
                    <i class="fas fa-{{ isset($product) ? 'save' : 'plus-circle' }}" id="submitIcon"></i>
                    <span id="submitText">{{ isset($product) ? 'Update Product' : 'Create Product' }}</span>
                </button>
                <a href="{{ route('admin.products') }}" class="btn btn-secondary" style="width:100%;margin-top:8px;justify-content:center">
                    Cancel
                </a>
            </div>

            {{-- PRODUCT SUMMARY (edit mode) --}}
            @isset($product)
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-chart-bar"></i> Product Info</div>
                <div style="display:flex;flex-direction:column;gap:10px">
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:var(--gray)">Product ID</span>
                        <strong>#{{ $product->id }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:var(--gray)">Slug</span>
                        <code style="font-size:11px;background:var(--light);padding:2px 6px;border-radius:4px">{{ $product->slug }}</code>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:var(--gray)">Created</span>
                        <strong>{{ $product->created_at->format('d M Y') }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:var(--gray)">Last Updated</span>
                        <strong>{{ $product->updated_at->format('d M Y') }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:var(--gray)">Times Ordered</span>
                        <strong>{{ $product->orderItems()->sum('quantity') }} units</strong>
                    </div>
                </div>
            </div>

            {{-- DANGER ZONE --}}
            <div class="form-section" style="border-color:#fecaca">
                <div class="form-section-title" style="color:#991b1b;border-color:#fecaca"><i class="fas fa-exclamation-triangle" style="color:#ef4444"></i> Danger Zone</div>
                <p style="font-size:12px;color:var(--gray);margin-bottom:12px">Permanently delete this product. This action cannot be undone.</p>
                <form action="{{ route('admin.products.delete', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                        <i class="fas fa-trash"></i> Delete Product
                    </button>
                </form>
            </div>
            @endisset

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// ── FILE UPLOAD ──────────────────────────────────────────────
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;
    showUploadPreview(file);
    // Clear URL field when file is selected
    document.getElementById('imageUrl').value = '';
    // Hide current image
    const cw = document.getElementById('currentImgWrap');
    if (cw) cw.style.display = 'none';
    const ei = document.getElementById('existingImageUrl');
    if (ei) ei.value = '';
}

function showUploadPreview(file) {
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPreview').src = e.target.result;
        document.getElementById('uploadFileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        document.getElementById('uploadContent').style.display = 'none';
        document.getElementById('uploadPreviewWrap').style.display = 'block';
        document.getElementById('uploadZone').style.borderColor = 'var(--primary)';
        document.getElementById('uploadZone').style.background = '#fffbf0';
    };
    reader.readAsDataURL(file);
}

function clearUpload(e) {
    e.stopPropagation();
    document.getElementById('imageFile').value = '';
    document.getElementById('uploadContent').style.display = 'block';
    document.getElementById('uploadPreviewWrap').style.display = 'none';
    document.getElementById('uploadZone').style.borderColor = 'var(--border)';
    document.getElementById('uploadZone').style.background = 'var(--light)';
    // Restore current image if editing
    const cw = document.getElementById('currentImgWrap');
    if (cw) cw.style.display = 'block';
    const ei = document.getElementById('existingImageUrl');
    if (ei) ei.value = document.getElementById('currentImg')?.src || '';
}

function removeCurrentImage() {
    document.getElementById('currentImgWrap').style.display = 'none';
    const ei = document.getElementById('existingImageUrl');
    if (ei) ei.value = '';
}

// Drag & drop
function dragOver(e) {
    e.preventDefault();
    document.getElementById('uploadZone').style.borderColor = 'var(--primary)';
    document.getElementById('uploadZone').style.background = '#fffbf0';
}
function dragLeave(e) {
    if (!document.getElementById('imageFile').files.length) {
        document.getElementById('uploadZone').style.borderColor = 'var(--border)';
        document.getElementById('uploadZone').style.background = 'var(--light)';
    }
}
function dropFile(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('imageFile').files = dt.files;
        showUploadPreview(file);
        document.getElementById('imageUrl').value = '';
        const cw = document.getElementById('currentImgWrap');
        if (cw) cw.style.display = 'none';
    }
}

// ── URL PREVIEW ──────────────────────────────────────────────
function previewUrl(url) {
    // Clear file input when URL is typed
    if (url) {
        document.getElementById('imageFile').value = '';
        document.getElementById('uploadContent').style.display = 'block';
        document.getElementById('uploadPreviewWrap').style.display = 'none';
        document.getElementById('uploadZone').style.borderColor = 'var(--border)';
        document.getElementById('uploadZone').style.background = 'var(--light)';
    }
}

// ── CHARACTER COUNTER ────────────────────────────────────────
function updateCharCount(el, countId, max) {
    const count = el.value.length;
    const el2 = document.getElementById(countId);
    el2.textContent = count + '/' + max;
    el2.className = 'char-count' + (count > max * 0.9 ? ' warn' : '') + (count >= max ? ' over' : '');
}

// ── DISCOUNT CALCULATOR ──────────────────────────────────────
function calcDiscount() {
    const price = parseFloat(document.getElementById('priceInput').value) || 0;
    const oldPrice = parseFloat(document.getElementById('oldPriceInput').value) || 0;
    const badge = document.getElementById('discountBadge');
    const text = document.getElementById('discountText');
    if (oldPrice > price && price > 0) {
        const pct = Math.round(((oldPrice - price) / oldPrice) * 100);
        text.textContent = pct + '% discount';
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

// ── SUBMIT LOADING STATE ─────────────────────────────────────
document.getElementById('productForm').addEventListener('submit', function() {
    const btn  = document.getElementById('submitBtn');
    const icon = document.getElementById('submitIcon');
    const text = document.getElementById('submitText');
    btn.disabled = true;
    btn.style.opacity = '0.8';
    icon.className = 'fas fa-spinner fa-spin';
    text.textContent = 'Saving… please wait';
});

// ── INIT ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    calcDiscount();
    updateCharCount(document.getElementById('productName'), 'nameCount', 255);
    updateCharCount(document.getElementById('productDesc'), 'descCount', 1000);
});
</script>
@endpush
