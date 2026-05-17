<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Medicine Shop</title>

    <link rel="stylesheet" href="login.css">

</head>
<body class="auth-body">

<div class="auth-shell">
    <div class="auth-side">
        <div class="logo-big">💊</div>
        <h1>Online Medicine Shop</h1>
        <p>Your digital pharmacy. Access quality healthcare products and manage prescriptions securely.</p>

        <ul class="feature-list">
            <li><span>✓</span> Verified Healthcare Products</li>
            <li><span>✓</span> Expert Prescription Management</li>
            <li><span>✓</span> Secure & Private Account</li>
            <li><span>✓</span> Fast Doorstep Delivery</li>
        </ul>
    </div>

    <div class="auth-form-wrap">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p class="muted">Enter your credentials to access your account</p>

            <!-- Error handling placeholder -->
            <!-- In PHP: <?php if(!empty($error)): ?><div class="alert-error"><?= $error ?></div><?php endif; ?> -->

            <form method="POST" action="index.php?page=login" class="form">
                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <p class="auth-foot">
                New to our shop? <a href="index.php?page=register">Create Account</a>
            </p>

            <div class="hint">
                <strong>Admin Demo:</strong>
                User: admin@gmail.com / Pass: admin123
            </div>
        </div>
    </div>
</div>

</body>
</html>