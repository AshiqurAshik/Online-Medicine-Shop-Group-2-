<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MediShield Global</title>

    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --secondary: #0369a1;
            --text-main: #111827;
            --text-muted: #6b7280;
            --bg-canvas: #f3f4f6;
            --white: #ffffff;
            --radius-lg: 24px;
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-canvas);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-shell {
            display: flex;
            width: 100%;
            max-width: 1050px;
            min-height: 650px;
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .branding-side {
            flex: 1;
            position: relative;
            background: linear-gradient(rgba(13, 148, 136, 0.85), rgba(3, 105, 161, 0.9)),
                        url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=1200&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            padding: 60px;
            color: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .branding-side .logo-icon { font-size: 48px; margin-bottom: 20px; }

        .branding-side h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.1;
        }

        .branding-side p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .trust-badges { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

        .badge {
            background: rgba(255, 255, 255, 0.15);
            padding: 12px;
            border-radius: 12px;
            backdrop-filter: blur(8px);
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-side {
            flex: 1;
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
        }

        .form-container {
            width: 100%;
            max-width: 380px;
        }

        .form-container h2 {
            font-size: 1.85rem;
            color: var(--text-main);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--text-muted);
            margin-bottom: 32px;
            font-size: 0.95rem;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1.4;
            border: 1px solid transparent;
        }

        .alert.error {
            background: #fff1f2;
            color: #be123c;
            border-color: #fecdd3;
        }

        .input-wrapper { margin-bottom: 20px; }

        .input-wrapper label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s;
            outline: none;
            background: #f9fafb;
        }

        .input-wrapper input:focus {
            background: var(--white);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember-check input {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
        }

        .btn-submit {
            width: 100%;
            background: var(--primary);
            color: var(--white);
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.2);
        }

        .footer-link {
            margin-top: 32px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .footer-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .footer-link a:hover { text-decoration: underline; }

       
    </style>
</head>
<body>

<div class="login-shell">
    <div class="branding-side">
        <div class="logo-icon">🏥</div>
        <h1>MediShield Global</h1>
        <p>Your secure gateway to professional clinical pharmacy services and laboratory-verified medicine.</p>
        <div class="trust-badges">
            <div class="badge"><span>🛡️</span> Secure SSL</div>
            <div class="badge"><span>🧪</span> Lab-Tested</div>
            <div class="badge"><span>🚚</span> Fast Track</div>
            <div class="badge"><span>👨‍⚕️</span> Expert Care</div>
        </div>
    </div>

    <div class="form-side">
        <div class="form-container">
            <h2>Welcome Back</h2>
            <p class="subtitle">Please enter your credentials to proceed.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert" style="background:#f0fdf4;color:#15803d;border-color:#bbf7d0">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form method="POST" action="index.php?page=login">
                <div class="input-wrapper">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           placeholder="Enter Email Address" required autofocus>
                </div>

                <div class="input-wrapper">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Enter Password" required>
                </div>

                <div class="options-row">
                    <label class="remember-check">
                        <input type="checkbox" name="remember" id="remember">
                        Remember Me
                    </label>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <p class="footer-link">
                Don't have an account? <a href="index.php?page=register">Register Now</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>