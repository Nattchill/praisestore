<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome – PraiseStore</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#0f0a1e;overflow:hidden}

        /* Animated gradient background */
        .bg{position:fixed;inset:0;background:linear-gradient(135deg,#1a0a2e 0%,#0f0a1e 40%,#1a0a2e 100%);z-index:0}
        .bg-orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.35;animation:float 6s ease-in-out infinite}
        .bg-orb-1{width:500px;height:500px;background:radial-gradient(circle,#bc1888,transparent);top:-100px;left:-100px;animation-delay:0s}
        .bg-orb-2{width:400px;height:400px;background:radial-gradient(circle,#f09433,transparent);bottom:-80px;right:-80px;animation-delay:2s}
        .bg-orb-3{width:300px;height:300px;background:radial-gradient(circle,#dc2743,transparent);top:50%;left:50%;transform:translate(-50%,-50%);animation-delay:4s}
        @keyframes float{0%,100%{transform:scale(1) translateY(0)}50%{transform:scale(1.08) translateY(-20px)}}

        /* Splash card */
        .splash{position:relative;z-index:1;text-align:center;padding:0 20px;animation:fadeUp .6s ease both}
        @keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}

        /* Logo */
        .logo-wrap{display:inline-flex;align-items:center;justify-content:center;width:90px;height:90px;border-radius:24px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);box-shadow:0 0 40px rgba(225,48,108,.5);margin-bottom:28px;animation:pulse 2s ease-in-out infinite}
        @keyframes pulse{0%,100%{box-shadow:0 0 40px rgba(225,48,108,.5)}50%{box-shadow:0 0 70px rgba(225,48,108,.8)}}
        .logo-wrap i{font-size:38px;color:#fff}

        /* Text */
        .brand-name{font-size:15px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:16px}
        .welcome-text{font-size:42px;font-weight:900;color:#fff;line-height:1.15;margin-bottom:10px}
        .welcome-text span{background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .sub-text{font-size:16px;color:rgba(255,255,255,.55);margin-bottom:40px;font-weight:400}

        /* Progress bar */
        .progress-wrap{width:280px;height:4px;background:rgba(255,255,255,.1);border-radius:2px;margin:0 auto 20px;overflow:hidden}
        .progress-bar{height:100%;border-radius:2px;background:linear-gradient(90deg,#f09433,#dc2743,#bc1888);width:0%;animation:progress {{ $redirect_delay }}s linear forwards}
        @keyframes progress{from{width:0%}to{width:100%}}

        .loading-text{font-size:13px;color:rgba(255,255,255,.35);letter-spacing:1px}

        /* Particles */
        .particle{position:fixed;width:4px;height:4px;border-radius:50%;pointer-events:none;animation:rise linear infinite;opacity:0}
        @keyframes rise{0%{transform:translateY(100vh) scale(0);opacity:0}10%{opacity:.6}90%{opacity:.3}100%{transform:translateY(-10vh) scale(1);opacity:0}}
    </style>
</head>
<body>
<div class="bg">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
</div>

<div class="splash">
    <div class="logo-wrap">
        <i class="fas fa-store"></i>
    </div>
    <div class="brand-name">PraiseStore</div>
    <div class="welcome-text">
        Welcome back,<br><span>{{ auth()->user()->name }}!</span>
    </div>
    <div class="sub-text">
        @if(auth()->user()->is_admin)
            Ready to manage your store 🚀
        @else
            Your fashion destination awaits 🛍️
        @endif
    </div>
    <div class="progress-wrap">
        <div class="progress-bar"></div>
    </div>
    <div class="loading-text">Loading your dashboard...</div>
</div>

<script>
    // Generate floating particles
    for (let i = 0; i < 18; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.cssText = `
            left:${Math.random()*100}vw;
            width:${2+Math.random()*4}px;
            height:${2+Math.random()*4}px;
            background:${['#f09433','#dc2743','#bc1888','#e6683c','#cc2366'][Math.floor(Math.random()*5)]};
            animation-duration:${4+Math.random()*6}s;
            animation-delay:${Math.random()*4}s;
        `;
        document.body.appendChild(p);
    }

    // Redirect after delay
    setTimeout(() => {
        window.location.href = '{{ $redirect_url }}';
    }, {{ $redirect_delay * 1000 }});
</script>
</body>
</html>
