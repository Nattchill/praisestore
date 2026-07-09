@extends('admin.layout')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('breadcrumb', 'Settings')

@push('styles')
<style>
    .settings-grid{display:grid;grid-template-columns:220px 1fr;gap:24px}
    .settings-nav{background:#fff;border:1px solid var(--border);border-radius:12px;padding:8px;height:fit-content}
    .settings-nav a{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13.5px;font-weight:500;color:var(--gray);transition:all .2s}
    .settings-nav a:hover,.settings-nav a.active{background:var(--light);color:var(--dark)}
    .settings-nav a.active{background:rgba(245,166,35,.1);color:var(--primary);font-weight:600}
    .settings-nav a i{width:16px;text-align:center}
    .settings-section{display:none}
    .settings-section.active{display:block}
    .section-title{font-size:16px;font-weight:700;margin-bottom:4px}
    .section-desc{font-size:13px;color:var(--gray);margin-bottom:24px}
    .divider{border:none;border-top:1px solid var(--border);margin:20px 0}
    @media(max-width:768px){.settings-grid{grid-template-columns:1fr}.settings-nav{display:flex;overflow-x:auto;gap:4px;padding:6px}}
</style>
@endpush

@section('content')

@php
    $saved = [];
    if (file_exists(storage_path('app/settings.json'))) {
        $saved = json_decode(file_get_contents(storage_path('app/settings.json')), true) ?? [];
    }
    $defaults = [
        'store_name' => 'PraiseStore',
        'store_email' => 'davidfnatt2002@gmail.com',
        'store_phone' => '+250 795 9151',
        'store_address' => 'Kigali, Rwanda',
        'currency' => 'RWF',
        'shipping_fee' => '2000',
        'store_description' => 'Your premier fashion destination in Rwanda.',
    ];
    $s = array_merge($defaults, $saved);
@endphp

<div class="settings-grid">
    {{-- SETTINGS NAV --}}
    <div class="settings-nav">
        <a href="#" class="active" onclick="showTab('store', this)"><i class="fas fa-store"></i> Store Info</a>
        <a href="#" onclick="showTab('account', this)"><i class="fas fa-user-shield"></i> Account</a>
        <a href="#" onclick="showTab('appearance', this)"><i class="fas fa-palette"></i> Appearance</a>
        <a href="#" onclick="showTab('shipping', this)"><i class="fas fa-truck"></i> Shipping</a>
    </div>

    <div>
        {{-- STORE INFO --}}
        <div class="settings-section active" id="tab-store">
            <div class="card">
                <div class="section-title">Store Information</div>
                <div class="section-desc">Basic details about your store that appear on the website.</div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Store Name *</label>
                            <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $s['store_name']) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Currency *</label>
                            <select name="currency" class="form-control">
                                <option value="RWF" {{ $s['currency']==='RWF'?'selected':'' }}>RWF – Rwandan Franc</option>
                                <option value="USD" {{ $s['currency']==='USD'?'selected':'' }}>USD – US Dollar</option>
                                <option value="EUR" {{ $s['currency']==='EUR'?'selected':'' }}>EUR – Euro</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Contact Email *</label>
                            <input type="email" name="store_email" class="form-control" value="{{ old('store_email', $s['store_email']) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="text" name="store_phone" class="form-control" value="{{ old('store_phone', $s['store_phone']) }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Store Address *</label>
                        <input type="text" name="store_address" class="form-control" value="{{ old('store_address', $s['store_address']) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Store Description</label>
                        <textarea name="store_description" class="form-control" rows="3">{{ old('store_description', $s['store_description']) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Default Shipping Fee (RWF) *</label>
                        <input type="number" name="shipping_fee" class="form-control" value="{{ old('shipping_fee', $s['shipping_fee']) }}" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
                </form>
            </div>
        </div>

        {{-- ACCOUNT --}}
        <div class="settings-section" id="tab-account">
            <div class="card" style="margin-bottom:20px">
                <div class="section-title">Admin Profile</div>
                <div class="section-desc">Your administrator account information.</div>
                <div style="display:flex;align-items:center;gap:16px;padding:16px;background:var(--light);border-radius:10px">
                    <div style="width:56px;height:56px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:#fff">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:16px;font-weight:700">{{ auth()->user()->name }}</div>
                        <div style="font-size:13px;color:var(--gray)">{{ auth()->user()->email }}</div>
                        <span class="badge badge-active" style="margin-top:4px">Administrator</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="section-title">Change Password</div>
                <div class="section-desc">Update your admin account password.</div>
                <form action="{{ route('admin.settings.password') }}" method="POST" style="max-width:400px">
                    @csrf
                    <div class="form-group">
                        <label>Current Password *</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>New Password *</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Update Password</button>
                </form>
            </div>
        </div>

        {{-- APPEARANCE --}}
        <div class="settings-section" id="tab-appearance">
            <div class="card">
                <div class="section-title">Appearance</div>
                <div class="section-desc">Customize the look and feel of your store.</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px">
                    @foreach(['#f5a623'=>'Orange (Default)','#3b82f6'=>'Blue','#10b981'=>'Green','#8b5cf6'=>'Purple','#ef4444'=>'Red','#1a1a2e'=>'Dark'] as $color => $name)
                    <div style="padding:14px;border:2px solid {{ $color === '#f5a623' ? $color : 'var(--border)' }};border-radius:10px;cursor:pointer;text-align:center;transition:border-color .2s" onclick="this.parentElement.querySelectorAll('div').forEach(d=>d.style.borderColor='var(--border)');this.style.borderColor='{{ $color }}'">
                        <div style="width:32px;height:32px;border-radius:50%;background:{{ $color }};margin:0 auto 8px"></div>
                        <div style="font-size:12px;font-weight:600">{{ $name }}</div>
                    </div>
                    @endforeach
                </div>
                <p style="font-size:13px;color:var(--gray);background:var(--light);padding:12px;border-radius:8px"><i class="fas fa-info-circle"></i> Color theme customization requires code changes in the CSS variables. Contact your developer to apply a new theme.</p>
            </div>
        </div>

        {{-- SHIPPING --}}
        <div class="settings-section" id="tab-shipping">
            <div class="card">
                <div class="section-title">Shipping Settings</div>
                <div class="section-desc">Configure delivery zones and fees.</div>
                <div style="display:flex;flex-direction:column;gap:12px">
                    @foreach(['Kigali City'=>2000,'Northern Province'=>3500,'Southern Province'=>3500,'Eastern Province'=>3500,'Western Province'=>4000] as $zone => $fee)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:var(--light);border-radius:8px;border:1px solid var(--border)">
                        <div>
                            <div style="font-size:14px;font-weight:600">{{ $zone }}</div>
                            <div style="font-size:12px;color:var(--gray)">Standard delivery 2–4 days</div>
                        </div>
                        <div style="font-size:15px;font-weight:700;color:var(--primary)">RWF {{ number_format($fee) }}</div>
                    </div>
                    @endforeach
                </div>
                <p style="font-size:13px;color:var(--gray);margin-top:16px;background:var(--light);padding:12px;border-radius:8px"><i class="fas fa-info-circle"></i> To modify shipping zones and fees, update the CheckoutController and this settings page.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showTab(name, el) {
    event.preventDefault();
    document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.settings-nav a').forEach(a => a.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    el.classList.add('active');
}

// Auto-open account tab if password error
@if($errors->has('current_password') || $errors->has('password'))
    document.querySelector('[onclick*="account"]').click();
@endif
</script>
@endpush
