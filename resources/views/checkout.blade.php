@extends('layouts.store')
@section('title', 'Checkout – PraiseStore')

@push('styles')
<style>
    .page-banner{background:var(--ig-gradient);color:#fff;padding:30px 0;margin-bottom:30px}
    .page-banner h1{font-size:28px;font-weight:700}
    .page-banner .breadcrumb{font-size:13px;color:rgba(255,255,255,.6)}
    .page-banner .breadcrumb a{color:#ffe0ef}
    .checkout-layout{display:grid;grid-template-columns:1fr 380px;gap:30px;padding-bottom:50px}

    .section-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:28px;margin-bottom:20px}
    .section-card h2{font-size:16px;font-weight:700;margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;color:var(--dark)}
    .section-card h2 i{background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .form-group label{display:block;font-size:13px;font-weight:600;color:var(--dark);margin-bottom:6px}
    .form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:14px;outline:none;transition:border-color .2s;font-family:inherit;background:var(--light)}
    .form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--primary);background:#fff;box-shadow:0 0 0 3px rgba(225,48,108,.08)}
    .form-error{color:#dc2626;font-size:12px;margin-top:4px}

    /* PAYMENT OPTIONS */
    .payment-options{display:flex;flex-direction:column;gap:10px}
    .payment-option{border:2px solid var(--border);border-radius:10px;cursor:pointer;transition:all .2s;overflow:hidden}
    .payment-option-header{display:flex;align-items:center;gap:12px;padding:14px 16px}
    .payment-option:has(input:checked){border-color:var(--primary)}
    .payment-option:has(input:checked) .payment-option-header{background:#fff0f5}
    .payment-option input[type=radio]{accent-color:var(--primary);width:16px;height:16px;flex-shrink:0}
    .payment-option label{cursor:pointer;font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px}
    .payment-option .desc{font-size:12px;color:#64748b;font-weight:400;margin-left:auto}

    /* MOMO PANEL */
    .momo-panel{display:none;padding:0 16px 16px}
    .momo-panel.show{display:block}

    .momo-steps{background:linear-gradient(135deg,#fff0f5,#fce7f3);border-radius:10px;padding:18px;margin-bottom:14px}
    .momo-steps h4{font-size:13px;font-weight:700;color:#9d174d;margin-bottom:12px;display:flex;align-items:center;gap:6px}
    .momo-step{display:flex;align-items:flex-start;gap:10px;margin-bottom:10px}
    .momo-step:last-child{margin-bottom:0}
    .step-num{width:22px;height:22px;background:var(--ig-gradient);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;margin-top:1px}
    .step-text{font-size:13px;color:#831843;line-height:1.5}
    .step-text strong{color:#0f172a}

    .momo-numbers{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px}
    .momo-number-card{background:#fff;border:1.5px solid #fbcfe8;border-radius:10px;padding:14px;text-align:center}
    .momo-number-card .network{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px}
    .momo-number-card .network.mtn{color:#f59e0b}
    .momo-number-card .network.airtel{color:#ef4444}
    .momo-number-card .number{font-size:18px;font-weight:800;color:#0f172a;letter-spacing:1px}
    .momo-number-card .name{font-size:11px;color:#64748b;margin-top:2px}

    .momo-alert{background:#fefce8;border:1.5px solid #fde68a;border-radius:8px;padding:12px 14px;font-size:12.5px;color:#92400e;display:flex;align-items:flex-start;gap:8px;margin-bottom:14px}

    /* ORDER SUMMARY */
    .order-summary{background:#fff;border:1px solid var(--border);border-radius:14px;padding:24px;height:fit-content;position:sticky;top:90px}
    .order-summary h3{font-size:16px;font-weight:700;margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--border)}
    .order-item{display:flex;align-items:center;gap:12px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border)}
    .order-item img{width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--border)}
    .order-item-info{flex:1}
    .order-item-info h4{font-size:13px;font-weight:600;margin-bottom:2px}
    .order-item-info span{font-size:12px;color:var(--gray)}
    .order-item-price{font-size:14px;font-weight:700;background:var(--ig-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .summary-row.total{font-size:18px;font-weight:800;border-top:2px solid var(--border);padding-top:14px;margin-top:4px;color:var(--dark)}

    .btn-submit{width:100%;padding:14px;background:var(--ig-gradient);color:#fff;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer;margin-top:16px;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s}
    .btn-submit:hover{opacity:.9;box-shadow:0 8px 20px rgba(225,48,108,.3)}

    .secure-note{display:flex;align-items:center;justify-content:center;gap:6px;font-size:12px;color:#64748b;margin-top:10px}

    @media(max-width:900px){.checkout-layout{grid-template-columns:1fr}.order-summary{position:static}}
    @media(max-width:600px){.form-row{grid-template-columns:1fr}.momo-numbers{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="page-banner">
    <div class="container">
        <h1><i class="fas fa-lock"></i> Secure Checkout</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('cart') }}">Cart</a> / Checkout</div>
    </div>
</div>

<div class="container">
    <div class="checkout-layout">
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf

            {{-- CUSTOMER INFO --}}
            <div class="section-card">
                <h2><i class="fas fa-user"></i> Customer Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required placeholder="John Doe">
                        @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required placeholder="john@example.com">
                        @error('customer_email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required placeholder="+250 7XX XXX XXX">
                        @error('customer_phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" value="{{ old('city', 'Kigali') }}" required placeholder="Kigali">
                        @error('city')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label>Shipping Address *</label>
                    <textarea name="shipping_address" rows="3" required placeholder="Street address, district, sector...">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- PAYMENT METHOD --}}
            <div class="section-card">
                <h2><i class="fas fa-credit-card"></i> Payment Method</h2>
                <div class="payment-options">

                    {{-- CASH ON DELIVERY --}}
                    <div class="payment-option">
                        <div class="payment-option-header">
                            <input type="radio" name="payment_method" id="cod" value="cash_on_delivery" {{ old('payment_method','cash_on_delivery') == 'cash_on_delivery' ? 'checked' : '' }} onchange="toggleMomo(false)">
                            <label for="cod">
                                <i class="fas fa-money-bill-wave" style="color:#10b981;font-size:18px"></i>
                                Cash on Delivery
                                <span class="desc">Pay when your order arrives</span>
                            </label>
                        </div>
                    </div>

                    {{-- MOBILE MONEY --}}
                    <div class="payment-option">
                        <div class="payment-option-header">
                            <input type="radio" name="payment_method" id="momo" value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'checked' : '' }} onchange="toggleMomo(true)">
                            <label for="momo">
                                <i class="fas fa-mobile-alt" style="color:var(--primary);font-size:18px"></i>
                                Mobile Money
                                <span class="desc">MTN MoMo / Airtel Money</span>
                            </label>
                        </div>

                        {{-- MOMO INSTRUCTIONS PANEL --}}
                        <div class="momo-panel {{ old('payment_method') == 'mobile_money' ? 'show' : '' }}" id="momo-panel">

                            {{-- SEND TO NUMBERS --}}
                            <div class="momo-numbers">
                                <div class="momo-number-card">
                                    <div class="network mtn">📱 MTN MoMo</div>
                                    <div class="number">0794 259 151</div>
                                    <div class="name">PraiseStore Ltd</div>
                                </div>
                                <div class="momo-number-card">
                                    <div class="network airtel">📱 Airtel Money</div>
                                    <div class="number">0778 268 118</div>
                                    <div class="name">PraiseStore Ltd</div>
                                </div>
                            </div>

                            {{-- STEP BY STEP --}}
                            <div class="momo-steps">
                                <h4><i class="fas fa-list-ol"></i> How to Pay with Mobile Money</h4>
                                <div class="momo-step">
                                    <div class="step-num">1</div>
                                    <div class="step-text">Open your <strong>MTN MoMo</strong> or <strong>Airtel Money</strong> app on your phone</div>
                                </div>
                                <div class="momo-step">
                                    <div class="step-num">2</div>
                                    <div class="step-text">Select <strong>"Send Money"</strong> or <strong>"Pay Merchant"</strong></div>
                                </div>
                                <div class="momo-step">
                                    <div class="step-num">3</div>
                                    <div class="step-text">Enter the number above for your network and send exactly <strong>RWF {{ number_format($total + 2000) }}</strong></div>
                                </div>
                                <div class="momo-step">
                                    <div class="step-num">4</div>
                                    <div class="step-text">Use your <strong>Order Number</strong> as the reference/reason for payment</div>
                                </div>
                                <div class="momo-step">
                                    <div class="step-num">5</div>
                                    <div class="step-text">Enter your MoMo phone number below and place your order — we'll confirm once payment is received</div>
                                </div>
                            </div>

                            {{-- ALERT --}}
                            <div class="momo-alert">
                                <i class="fas fa-exclamation-triangle" style="margin-top:1px;flex-shrink:0"></i>
                                <span>Your order will be <strong>held for 2 hours</strong> while we verify your payment. Orders not paid within 2 hours may be cancelled.</span>
                            </div>

                            {{-- MOMO PHONE INPUT --}}
                            <div class="form-group" style="margin-bottom:0">
                                <label>Your Mobile Money Phone Number *</label>
                                <input type="text" name="momo_phone" id="momo_phone" value="{{ old('momo_phone') }}" placeholder="e.g. 0788 123 456" style="background:#fff">
                                @error('momo_phone')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-lock"></i> Place Order Securely
            </button>
            <div class="secure-note"><i class="fas fa-shield-alt"></i> Your information is encrypted and secure</div>
        </form>

        {{-- ORDER SUMMARY --}}
        <div class="order-summary">
            <h3><i class="fas fa-receipt" style="color:var(--primary)"></i> Order Summary</h3>
            @foreach($items as $item)
            <div class="order-item">
                <img src="{{ $item['image'] ?? 'https://via.placeholder.com/56' }}" alt="{{ $item['name'] }}">
                <div class="order-item-info">
                    <h4>{{ $item['name'] }}</h4>
                    <span>Qty: {{ $item['quantity'] }}</span>
                </div>
                <div class="order-item-price">RWF {{ number_format($item['price'] * $item['quantity']) }}</div>
            </div>
            @endforeach
            <div class="summary-row"><span>Subtotal</span><span>RWF {{ number_format($total) }}</span></div>
            <div class="summary-row"><span>Shipping</span><span>RWF {{ number_format($shipping) }}</span></div>
            <div class="summary-row total"><span>Total</span><span>RWF {{ number_format($total + $shipping) }}</span></div>

            <div style="margin-top:16px;padding:12px;background:#fff0f5;border-radius:8px;font-size:12px;color:#9d174d">
                <i class="fas fa-info-circle"></i> By placing your order you agree to our <a href="#" style="color:var(--primary);font-weight:600">Terms & Conditions</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleMomo(show) {
    const panel = document.getElementById('momo-panel');
    const momoPhone = document.getElementById('momo_phone');
    if (show) {
        panel.classList.add('show');
        momoPhone.required = true;
    } else {
        panel.classList.remove('show');
        momoPhone.required = false;
    }
}
// Init on page load
document.addEventListener('DOMContentLoaded', () => {
    const momo = document.getElementById('momo');
    if (momo && momo.checked) toggleMomo(true);
});
</script>
@endpush
