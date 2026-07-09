<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – PraiseStore</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;background:#0f172a}
        a{text-decoration:none;color:inherit}

        .left{flex:1;background:linear-gradient(135deg,#833ab4 0%,#fd1d1d 50%,#fcb045 100%);display:flex;flex-direction:column;justify-content:center;align-items:center;padding:60px;position:relative;overflow:hidden}
        .left::before{content:'';position:absolute;top:-80px;right:-80px;width:400px;height:400px;background:rgba(255,255,255,.07);border-radius:50%}
        .left::after{content:'';position:absolute;bottom:-100px;left:-60px;width:300px;height:300px;background:rgba(255,255,255,.05);border-radius:50%}
        .brand{display:flex;align-items:center;gap:14px;margin-bottom:60px;position:relative;z-index:1;text-decoration:none;cursor:pointer}
        .brand-icon{width:52px;height:52px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;backdrop-filter:blur(10px)}
        .brand-name{font-size:26px;font-weight:800;color:#fff;letter-spacing:-.5px}
        .brand-name span{color:#ffd6e7}
        .left-content{position:relative;z-index:1;text-align:center;max-width:380px}
        .left-content h1{font-size:36px;font-weight:800;color:#fff;line-height:1.2;margin-bottom:16px}
        .left-content h1 span{color:#ffd6e7}
        .left-content p{color:rgba(255,255,255,.8);font-size:15px;line-height:1.7;margin-bottom:40px}
        .steps{display:flex;flex-direction:column;gap:16px;text-align:left}
        .step{display:flex;align-items:center;gap:14px}
        .step-num{width:32px;height:32px;background:rgba(255,255,255,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
        .step-text{color:rgba(255,255,255,.85);font-size:14px}
        .step-text strong{color:#fff;display:block;font-size:14px}

        .right{flex:1;max-width:540px;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:50px 56px;overflow-y:auto}
        .form-header{margin-bottom:30px}
        .form-header h2{font-size:26px;font-weight:800;color:#0f172a;margin-bottom:8px}
        .form-header p{color:#64748b;font-size:14px}
        .form-header p a{color:#e1306c;font-weight:600}
        .form-control:focus{border-color:#e1306c;background:#fff;box-shadow:0 0 0 4px rgba(225,48,108,.08)}
        .btn-submit{width:100%;padding:13px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:8px}
        .btn-submit:hover{opacity:.9;transform:translateY(-1px);box-shadow:0 8px 20px rgba(225,48,108,.35)}
        .btn-google{width:100%;padding:12px;background:#fff;color:#3c4043;border:1.5px solid #dadce0;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:10px;text-decoration:none}
        .btn-google:hover{background:#f8f9fa;box-shadow:0 2px 8px rgba(0,0,0,.12);border-color:#c0c0c0}
        .btn-google img{width:18px;height:18px}
        .login-link a{color:#e1306c;font-weight:700}
        .divider{display:flex;align-items:center;gap:12px;margin:16px 0;color:#9ca3af;font-size:13px}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:#e5e7eb}

        .pwd-strength{height:4px;border-radius:2px;margin-top:6px;background:#e5e7eb;overflow:hidden}
        .pwd-strength-bar{height:100%;border-radius:2px;transition:width .3s,background .3s;width:0}

        .form-group{margin-bottom:16px}
        .form-group label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px}
        .input-wrap{position:relative;display:flex;align-items:center}
        .input-wrap .icon{position:absolute;left:14px;color:#9ca3af;font-size:13px;pointer-events:none}
        .form-control{width:100%;padding:11px 14px 11px 38px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;font-family:inherit;color:#0f172a;background:#f9fafb;transition:all .2s;outline:none}
        .form-control:focus{border-color:#e1306c;background:#fff;box-shadow:0 0 0 4px rgba(225,48,108,.08)}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        .form-error{font-size:12px;color:#e1306c;margin-top:4px;display:flex;align-items:center;gap:4px}
        .alert-error{background:#fff0f5;border:1px solid #fbcfe8;color:#9d174d;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px}
        @media(max-width:900px){.left{display:none}.right{max-width:100%;padding:40px 28px}}
        @media(max-width:480px){.form-row{grid-template-columns:1fr}}
    </style>
</head>
<body>

<div class="left">
    <a href="{{ route('home') }}" class="brand">
        <div class="brand-icon"><i class="fas fa-store"></i></div>
        <div class="brand-name">Praise<span>Store</span></div>
    </a>
    <div class="left-content">
        <h1>Join <span>PraiseStore</span> Today</h1>
        <p>Create your free account and start shopping the best fashion in Rwanda.</p>
        <div class="steps">
            <div class="step"><div class="step-num">1</div><div class="step-text"><strong>Create your account</strong>Fill in your details below</div></div>
            <div class="step"><div class="step-num">2</div><div class="step-text"><strong>Verify your email</strong>Check your inbox for a link</div></div>
            <div class="step"><div class="step-num">3</div><div class="step-text"><strong>Start shopping</strong>Browse hundreds of fashion items</div></div>
        </div>
    </div>
</div>

<div class="right">
    <div class="form-header">
        <h2>Create your account ✨</h2>
        <p>Already have one? <a href="{{ route('login') }}">Sign in here</a></p>
    </div>

    @if($errors->any())
    <div class="alert-error"><i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0"></i><div>{{ $errors->first() }}</div></div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label>Full Name</label>
            <div class="input-wrap">
                <i class="fas fa-user icon"></i>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="David Natt" required autofocus>
            </div>
            @error('name')<div class="form-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <div class="input-wrap">
                <i class="fas fa-envelope icon"></i>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
            </div>
            @error('email')<div class="form-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password" id="pwd" class="form-control" placeholder="Min. 8 chars" required oninput="checkStrength(this.value)">
                    <button type="button" onclick="togglePwd('pwd','eye1')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;font-size:13px"><i class="fas fa-eye" id="eye1"></i></button>
                </div>
                <div class="pwd-strength"><div class="pwd-strength-bar" id="strength-bar"></div></div>
                @error('password')<div class="form-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password_confirmation" id="pwd2" class="form-control" placeholder="Repeat password" required>
                    <button type="button" onclick="togglePwd('pwd2','eye2')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;font-size:13px"><i class="fas fa-eye" id="eye2"></i></button>
                </div>
            </div>
        </div>

        @if(Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="form-group" style="margin-bottom:8px">
            <label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;font-weight:400;color:#374151">
                <input type="checkbox" name="terms" required style="margin-top:2px;accent-color:#e1306c">
                <span style="font-size:13px">I agree to the <a href="{{ route('terms.show') }}" target="_blank" style="color:#e1306c;font-weight:600">Terms of Service</a> and <a href="{{ route('policy.show') }}" target="_blank" style="color:#e1306c;font-weight:600">Privacy Policy</a></span>
            </label>
        </div>
        @endif

        <button type="submit" class="btn-submit" onclick="this.disabled=true;this.form.submit()"><i class="fas fa-user-plus" style="pointer-events:none"></i> Create Account</button>
    </form>

    <div class="divider">or sign up with</div>
    <a href="{{ route('auth.google') }}" class="btn-google">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
        Continue with Google
    </a>

    <div class="login-link" style="margin-top:20px">Already have an account? <a href="{{ route('login') }}">Sign in</a></div>
</div>

<script>
function togglePwd(id, iconId) {
    const el = document.getElementById(id);
    const icon = document.getElementById(iconId);
    if (el.type === 'password') { el.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else { el.type = 'password'; icon.className = 'fas fa-eye'; }
}
function checkStrength(val) {
    const bar = document.getElementById('strength-bar');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    bar.style.width = (score * 25) + '%';
    bar.style.background = colors[score - 1] || '#e5e7eb';
}
</script>
</body>
</html>
