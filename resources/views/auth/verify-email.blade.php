<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email – PraiseStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 50%,#1d4ed8 100%);display:flex;align-items:center;justify-content:center;padding:20px}
        .card{background:#fff;border-radius:20px;padding:50px 44px;max-width:480px;width:100%;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,.3)}
        .icon-wrap{width:80px;height:80px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:32px;color:#2563eb}
        h1{font-size:24px;font-weight:800;color:#0f172a;margin-bottom:12px}
        p{color:#64748b;font-size:14px;line-height:1.7;margin-bottom:28px}
        .email-badge{display:inline-flex;align-items:center;gap:8px;background:#eff6ff;color:#1d4ed8;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;margin-bottom:28px}
        .alert-success{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:24px;display:flex;align-items:center;gap:8px}
        .btn-primary{display:inline-flex;align-items:center;gap:8px;width:100%;padding:13px;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;justify-content:center;transition:all .2s;margin-bottom:14px}
        .btn-primary:hover{background:linear-gradient(135deg,#1e40af,#1d4ed8);box-shadow:0 8px 20px rgba(37,99,235,.3)}
        .actions{display:flex;align-items:center;justify-content:space-between;margin-top:20px;padding-top:20px;border-top:1px solid #f1f5f9}
        .link{font-size:13px;color:#2563eb;font-weight:600;cursor:pointer;background:none;border:none;font-family:inherit}
        .link:hover{text-decoration:underline}
        .brand{display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:32px}
        .brand-icon{width:40px;height:40px;background:linear-gradient(135deg,#1d4ed8,#2563eb);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;color:#fff}
        .brand-name{font-size:20px;font-weight:800;color:#0f172a}
        .brand-name span{color:#2563eb}
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="brand-icon"><i class="fas fa-store"></i></div>
        <div class="brand-name">Praise<span>Store</span></div>
    </div>

    <div class="icon-wrap"><i class="fas fa-envelope-open-text"></i></div>
    <h1>Check your inbox</h1>
    <p>We've sent a verification link to your email address. Click the link to activate your account and start shopping.</p>

    <div class="email-badge"><i class="fas fa-envelope"></i> {{ auth()->user()->email }}</div>

    @if(session('status') == 'verification-link-sent')
    <div class="alert-success"><i class="fas fa-check-circle"></i> A new verification link has been sent to your email!</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-primary"><i class="fas fa-paper-plane"></i> Resend Verification Email</button>
    </form>

    <div class="actions">
        <a href="{{ route('profile.show') }}" class="link"><i class="fas fa-user-edit"></i> Edit Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="link" style="color:#64748b"><i class="fas fa-sign-out-alt"></i> Log Out</button>
        </form>
    </div>
</div>
</body>
</html>
