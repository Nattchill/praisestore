@extends('customer.layout')
@section('title', 'My Profile')

@section('content')

{{-- PROFILE INFO --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-user-edit"></i> Personal Information</h2>
    </div>

    <div style="display:flex;align-items:center;gap:20px;padding:16px;background:var(--light);border-radius:10px;margin-bottom:24px">
        <div style="position:relative;flex-shrink:0">
            @if(auth()->user()->profile_photo_path)
                <img id="avatar-preview" src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="Avatar"
                    style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--primary)">
            @else
                <div id="avatar-initials" style="width:72px;height:72px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <img id="avatar-preview" src="" alt="Avatar"
                    style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);display:none">
            @endif
            <label for="avatar-input" style="position:absolute;bottom:0;right:0;width:24px;height:24px;background:var(--dark);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid #fff">
                <i class="fas fa-camera" style="font-size:10px;color:#fff"></i>
            </label>
        </div>
        <div>
            <div style="font-size:18px;font-weight:700">{{ auth()->user()->name }}</div>
            <div style="font-size:13px;color:var(--gray)">{{ auth()->user()->email }}</div>
            <div style="font-size:12px;color:var(--gray);margin-top:3px"><i class="fas fa-calendar"></i> Member since {{ auth()->user()->created_at->format('F Y') }}</div>
        </div>
    </div>

    {{-- Avatar upload form (hidden, submits on file select) --}}
    <form id="avatar-form" action="{{ route('customer.profile.avatar') }}" method="POST" enctype="multipart/form-data" style="display:none">
        @csrf
        <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp">
    </form>

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-group" style="max-width:400px">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="+250 7XX XXX XXX">
            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
    </form>
</div>

{{-- CHANGE PASSWORD --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-lock"></i> Change Password</h2>
    </div>
    <form action="{{ route('customer.profile.password') }}" method="POST" style="max-width:440px">
        @csrf
        <div class="form-group">
            <label>Current Password *</label>
            <div style="position:relative">
                <input type="password" name="current_password" id="cp" class="form-control" required style="padding-right:42px">
                <button type="button" onclick="togglePwd('cp',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gray)"><i class="fas fa-eye"></i></button>
            </div>
            @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>New Password *</label>
            <div style="position:relative">
                <input type="password" name="password" id="np" class="form-control" required minlength="8" style="padding-right:42px">
                <button type="button" onclick="togglePwd('np',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gray)"><i class="fas fa-eye"></i></button>
            </div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Confirm New Password *</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
        </div>
        <div style="background:#fffbf0;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:12px;color:#92400e;margin-bottom:16px">
            <i class="fas fa-info-circle"></i> Password must be at least 8 characters long.
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Update Password</button>
    </form>
</div>

{{-- ACCOUNT DANGER ZONE --}}
<div class="card" style="border-color:#fecaca">
    <div class="card-header" style="border-color:#fecaca">
        <h2 style="color:#991b1b"><i class="fas fa-exclamation-triangle" style="color:#ef4444"></i> Account</h2>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div>
            <div style="font-size:14px;font-weight:600;margin-bottom:3px">Sign out of your account</div>
            <div style="font-size:12px;color:var(--gray)">You will be redirected to the login page.</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
}
document.getElementById('avatar-input').addEventListener('change', function () {
    if (!this.files.length) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatar-preview');
        const initials = document.getElementById('avatar-initials');
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (initials) initials.style.display = 'none';
    };
    reader.readAsDataURL(this.files[0]);
    document.getElementById('avatar-form').submit();
});
</script>
@endpush
