<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal Login | Italian Pizza Hut</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 40px 34px;
            width: 100%;
            max-width: 410px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
        }
        .logo-box {
            margin-bottom: 24px;
        }
        .site-logo-img {
            max-height: 48px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
        }
        .brand-fallback {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .brand-fallback-icon {
            width: 44px;
            height: 44px;
            background: #c8102e;
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .brand-fallback-text {
            text-align: left;
            line-height: 1.1;
        }
        .brand-fallback-text strong {
            display: block;
            font-size: 1.5rem;
            font-weight: 900;
            color: #c8102e;
            font-family: 'Playfair Display', Georgia, serif;
        }
        .brand-fallback-text span {
            display: block;
            font-size: 0.68rem;
            color: #64748b;
            letter-spacing: 2px;
            font-weight: 700;
        }
        .input-group-clean {
            position: relative;
            margin-bottom: 16px;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
        }
        .input-field {
            width: 100%;
            padding: 13px 16px 13px 42px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.92rem;
            color: #0f172a;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            background: #fff;
            border-color: #c8102e;
            box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
        }
        .input-field:focus ~ .input-icon {
            color: #c8102e;
        }
        .pwd-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            font-size: 1rem;
        }
        .pwd-toggle:hover {
            color: #475569;
        }
        .btn-submit {
            width: 100%;
            background: #c8102e;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 0.96rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }
        .btn-submit:hover {
            background: #b00d28;
            transform: translateY(-1px);
        }
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.84rem;
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    @php
        $siteLogo = \App\Models\Setting::getValue('site_logo');
    @endphp

    <div class="login-card">
        <!-- Logo -->
        <div class="logo-box">
            @if(!empty($siteLogo) && file_exists(public_path('uploads/' . $siteLogo)))
                <img src="{{ asset('uploads/' . $siteLogo) }}" alt="Logo" class="site-logo-img">
            @else
                <div class="brand-fallback">
                    <span class="brand-fallback-icon"><i class="fa-solid fa-pizza-slice"></i></span>
                    <div class="brand-fallback-text">
                        <strong>Italian Pizza Hut</strong>
                        <span>ADMIN PORTAL</span>
                    </div>
                </div>
            @endif
        </div>

        @if(isset($errors) && $errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" style="text-align: left;">
            @csrf

            <!-- Email Field -->
            <div class="input-group-clean">
                <input 
                    type="email" 
                    name="email" 
                    class="input-field" 
                    placeholder="Admin Email Address" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                >
                <i class="fa-regular fa-envelope input-icon"></i>
            </div>

            <!-- Password Field -->
            <div class="input-group-clean">
                <input 
                    type="password" 
                    name="password" 
                    id="adminPassword" 
                    class="input-field" 
                    placeholder="Password" 
                    required
                >
                <i class="fa-solid fa-lock input-icon"></i>
                <button type="button" class="pwd-toggle" id="toggleAdminPwd" title="Toggle password visibility">
                    <i class="fa-regular fa-eye" id="toggleAdminIcon"></i>
                </button>
            </div>

            <button type="submit" class="btn-submit">
                <span>Access Dashboard</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleAdminPwd');
        const pwdInput = document.getElementById('adminPassword');
        const toggleIcon = document.getElementById('toggleAdminIcon');

        if (toggleBtn && pwdInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                if (pwdInput.type === 'password') {
                    pwdInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    pwdInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        }
    </script>
</body>
</html>
