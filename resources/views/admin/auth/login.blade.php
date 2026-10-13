<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Italian Pizza</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
        }
        .logo-box {
            width: 48px;
            height: 48px;
            background: #c8102e;
            color: #fff;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(200, 16, 46, 0.25);
        }
        h2 { font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        p { font-size: 0.85rem; color: #64748b; margin-bottom: 24px; }
        .form-group { text-align: left; margin-bottom: 16px; }
        label { display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 6px; }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: #c8102e; }
        .btn-submit {
            width: 100%;
            background: #c8102e;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(200, 16, 46, 0.25);
            transition: background 0.2s;
        }
        .btn-submit:hover { background: #a50b24; }
        .alert-error {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.82rem;
            margin-bottom: 16px;
            text-align: left;
        }
        .hint-box {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.78rem;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-box">🍕</div>
        <h2>Admin & POS Portal</h2>
        <p>Login to manage restaurant tables, orders & products</p>

        @if(isset($errors) && $errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Admin Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'admin@italianpizza.com') }}" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" value="password123" required>
            </div>

            <button type="submit" class="btn-submit">
                Access Admin Dashboard ➔
            </button>
        </form>

        <div class="hint-box">
            Default Credentials:<br>
            Email: <strong>admin@italianpizza.com</strong><br>
            Password: <strong>password123</strong>
        </div>
    </div>
</body>
</html>
