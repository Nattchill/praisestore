@extends('admin.layout')
@section('title', 'Categories')
@section('page-title', 'Category Management')
@section('breadcrumb', 'Categories')

@push('styles')
<style>
    .cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-bottom:24px}
    .cat-card{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:box-shadow .2s,transform .2s}
    .cat-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.08);transform:translateY(-2px)}
    .cat-img{height:130px;overflow:hidden;position:relative}
    .cat-img img{width:100%;height:100%;object-fit:cover}
    .cat-img-placeholder{width:100%;height:100%;background:linear-gradient(135deg,#f3f4f6,#e5e7eb);display:flex;align-items:center;justify-content:center;font-size:36px;color:#d1d5db}
    .cat-body{padding:14px}
    .cat-body h3{font-size:14px;font-weight:700;margin-bottom:4px}
    .cat-body span{font-size:12px;color:var(--gray)}
    .cat-actions{display:flex;gap:6px;margin-top:12px}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <div>
        <h2 style="font-size:15px;font-weight:700">All Categories</h2>
        <p style="font-size:13px;color:var(--gray);margin-top:2px">{{ $categories->total() }} categories total</p>
    </div>
    <button onclick="openModal('createModal')" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</button>
</div>

{{-- CATEGORY CARDS --}}
<div class="cat-grid">
    @foreach($categories as $cat)
    <div class="cat-card">
        <div class="cat-img">
            @if($cat->image)
                <img src="{{ $cat->image }}" alt="{{ $cat->name }}">
            @else
                <div class="cat-img-placeholder"><i class="fas fa-tags"></i></div>
            @endif
        </div>
        <div class="cat-body">
            <h3>{{ $cat->name }}</h3>
            <span>{{ $cat->products_count }} products &nbsp;·&nbsp; /{{ $cat->slug }}</span>
            <div class="cat-actions">
                <button onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->image ?? '') }}')" class="btn btn-secondary btn-sm" style="flex:1"><i class="fas fa-edit"></i> Edit</button>
                <form action="{{ route('admin.categories.delete', $cat) }}" method="POST" onsubmit="return confirm('Delete {{ $cat->name }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="pagination-wrap">{{ $categories->links() }}</div>

{{-- CREATE MODAL --}}
<div class="modal-overlay" id="createModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus" style="color:var(--primary)"></i> Add New Category</h3>
            <button class="modal-close" onclick="closeModal('createModal')">&times;</button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Summer Collection">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Image URL (optional)</label>
                <input type="url" name="image" class="form-control" placeholder="https://...">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Category</button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color:var(--primary)"></i> Edit Category</h3>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Image URL (optional)</label>
                <input type="url" name="image" id="editImage" class="form-control" placeholder="https://...">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Category</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function openEdit(id, name, image) {
    document.getElementById('editName').value = name;
    document.getElementById('editImage').value = image;
    document.getElementById('editForm').action = '/admin/categories/' + id;
    openModal('editModal');
}

// Auto-open create modal if validation errors
@if($errors->any())
    openModal('createModal');
@endif
</script>
@endpush
